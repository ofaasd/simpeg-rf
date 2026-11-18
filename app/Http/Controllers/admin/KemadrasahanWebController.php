<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPenilaianKemadrasahan;
use App\Models\LaporanBulananKemadrasahan;
use App\Models\RefMapel;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KemadrasahanWebController extends Controller
{
    /**
     * Nama-nama bulan dalam bahasa Indonesia.
     */
    protected array $bulanList = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];

    /**
     * Memeriksa apakah user yang sedang login adalah Administrator.
     */
    protected function isAdmin($user = null): bool
    {
        $user = $user ?? Auth::user();
        if (!$user) return false;

        if (method_exists($user, 'hasAnyRole')) {
            if ($user->hasAnyRole(['admin', 'admin_pondok', 'admin-input'])) {
                return true;
            }
        }

        if ($user->roles) {
            $roleNames = $user->roles->pluck('name')->toArray();
            if (count(array_intersect($roleNames, ['admin', 'admin_pondok', 'admin-input'])) > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Memeriksa apakah user memiliki akses ke kelas tertentu.
     * Admin: Akses ke seluruh kelas.
     * Ustadz: Hanya akses ke kelas yang ditugaskan padanya (ref_kelas.employee_id = user.pegawai_id).
     */
    protected function checkClassAccess($kodeKelas, $user = null): bool
    {
        $user = $user ?? Auth::user();
        if (!$user) return false;

        if ($this->isAdmin($user)) {
            return true;
        }

        if (empty($kodeKelas)) {
            return false;
        }

        $allowedKelas = DB::table('ref_kelas')
            ->where('employee_id', $user->pegawai_id)
            ->pluck('code')
            ->map(fn($c) => strtolower($c))
            ->toArray();

        return in_array(strtolower($kodeKelas), $allowedKelas);
    }

    /**
     * Mendapatkan daftar kode kelas yang boleh diakses user saat ini.
     */
    protected function getAllowedClassCodes($user = null): array
    {
        $user = $user ?? Auth::user();
        if (!$user) return [];

        if ($this->isAdmin($user)) {
            return DB::table('ref_kelas')->whereNotNull('code')->pluck('code')->toArray();
        }

        return DB::table('ref_kelas')
            ->where('employee_id', $user->pegawai_id)
            ->whereNotNull('code')
            ->pluck('code')
            ->toArray();
    }

    /**
     * Halaman Utama Manajemen Kemadrasahan.
     */
    public function index(Request $request)
    {
        $currentUser = Auth::user();
        $isAdmin = $this->isAdmin($currentUser);

        // Query dasar kelas beserta wali kelas dan akun user-nya
        $kelasQuery = DB::table('ref_kelas')
            ->leftJoin('employee_new', 'ref_kelas.employee_id', '=', 'employee_new.id')
            ->leftJoin('users', 'users.pegawai_id', '=', 'employee_new.id')
            ->select(
                'ref_kelas.code as kode_kelas',
                'ref_kelas.name as nama_kelas',
                'employee_new.nama as nama_wali_kelas',
                'users.id as user_id',
                'users.email'
            )
            ->whereNotNull('ref_kelas.code')
            ->orderBy('ref_kelas.code');

        // RESTRICTION: Jika bukan admin, hanya ambil kelas yang diampu oleh ustadz yang sedang login
        if (!$isAdmin && $currentUser) {
            $kelasQuery->where('ref_kelas.employee_id', $currentUser->pegawai_id);
        }

        $kelasList = $kelasQuery->get();
        $isWaliKelas = $kelasList->isNotEmpty();

        $defaultUserId = null;
        $defaultKelasCode = null;

        if (!$isAdmin && $currentUser) {
            $defaultUserId = $currentUser->id;
            if ($isWaliKelas) {
                $defaultKelasCode = $kelasList->first()->kode_kelas;
            }
        } else {
            // Jika admin, pilih kelas pertama atau kelas dari wali kelas pertama
            if ($kelasList->isNotEmpty()) {
                $firstKelas = $kelasList->first();
                $defaultUserId = $firstKelas->user_id ?? $currentUser->id;
                $defaultKelasCode = $firstKelas->kode_kelas;
            }
        }

        $currentMonth = (int) date('n');
        $currentSemester = in_array($currentMonth, [7, 8, 9, 10, 11, 12]) ? 1 : 2;

        // Ambil master mata pelajaran langsung dari database
        $mapelList = RefMapel::select('id', 'nama')->orderBy('nama')->get();
        $bulanList = $this->bulanList;

        return view('admin.kemadrasahan.index', compact(
            'kelasList',
            'mapelList',
            'defaultUserId',
            'defaultKelasCode',
            'bulanList',
            'currentMonth',
            'currentSemester',
            'isAdmin',
            'isWaliKelas'
        ));
    }

    /**
     * AJAX: Mengambil daftar santri berdasarkan kode kelas atau idUser wali kelas.
     * Menggunakan pengecekan hak akses kelas untuk ustadz.
     */
    public function getSantri(Request $request, $idUserOrKelas)
    {
        try {
            $currentUser = Auth::user();

            // Cek apakah parameter berupa idUser (numerik) atau kode_kelas (string)
            if (is_numeric($idUserOrKelas)) {
                $kelas = DB::table('ref_kelas')
                    ->leftJoin('employee_new', 'ref_kelas.employee_id', '=', 'employee_new.id')
                    ->leftJoin('users', 'users.pegawai_id', '=', 'employee_new.id')
                    ->where('users.id', $idUserOrKelas)
                    ->value('ref_kelas.code');
            } else {
                $kelas = $idUserOrKelas;
            }

            if (!$kelas) {
                return response()->json([
                    'success' => false,
                    'status'  => 'error',
                    'message' => 'Kelas tidak ditemukan.',
                    'data'    => []
                ], 404);
            }

            // RESTRICTION: Validasi hak akses kelas bagi ustadz
            if (!$this->checkClassAccess($kelas, $currentUser)) {
                return response()->json([
                    'success' => false,
                    'status'  => 'error',
                    'message' => 'Akses ditolak: Anda hanya dapat mengakses daftar santri di kelas yang Anda ampu.',
                    'data'    => []
                ], 403);
            }

            $santri = Santri::select([
                    'no_induk as noInduk',
                    'nama as namaSantri',
                    'photo as fotoSantri',
                    'kelas as kodeKelas',
                    'jenis_kelamin as jenisKelamin',
                ])
                ->where('kelas', $kelas)
                ->orderBy('nama', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'data'    => $santri
            ]);
        } catch (\Exception $e) {
            Log::error('KemadrasahanWebController@getSantri Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Gagal mengambil data santri: ' . $e->getMessage(),
                'data'    => []
            ], 500);
        }
    }

    /**
     * AJAX: Mengambil master mata pelajaran.
     */
    public function getMapel()
    {
        try {
            $mapel = RefMapel::select('id', 'nama')->orderBy('nama')->get();
            return response()->json([
                'success' => true,
                'status'  => 'success',
                'data'    => $mapel
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => $e->getMessage(),
                'data'    => []
            ], 500);
        }
    }

    /**
     * AJAX: Mengambil rekap nilai santri (Ganjil & Genap) berdasarkan no_induk.
     * Menggunakan pengecekan hak akses kelas untuk ustadz.
     */
    public function getDataSantri($noInduk)
    {
        try {
            $currentUser = Auth::user();

            $santri = Santri::select([
                    'no_induk as noInduk',
                    'nama as namaSantri',
                    'photo as fotoSantri',
                    'kelas as kodeKelas',
                    'jenis_kelamin as jenisKelamin'
                ])
                ->where('no_induk', $noInduk)
                ->first();

            if (!$santri) {
                return response()->json([
                    'success' => false,
                    'status'  => 'error',
                    'message' => 'Santri dengan No. Induk ' . $noInduk . ' tidak ditemukan.',
                    'data'    => null
                ], 404);
            }

            // RESTRICTION: Validasi hak akses ustadz terhadap santri ini
            if (!$this->checkClassAccess($santri->kodeKelas, $currentUser)) {
                return response()->json([
                    'success' => false,
                    'status'  => 'error',
                    'message' => 'Akses ditolak: Anda hanya dapat melihat rekap santri di kelas yang Anda ampu.',
                    'data'    => null
                ], 403);
            }

            // Ambil semua laporan bulanan beserta relasi detail penilaian, mapel, dan ustadz pengampu
            $laporanList = LaporanBulananKemadrasahan::with([
                'details.mapel',
                'details.pengampu.pegawai'
            ])
            ->where('no_induk', $noInduk)
            ->get();

            $ganjil = [];
            $genap = [];

            foreach ($laporanList as $lap) {
                $namaBulan = $this->bulanList[$lap->bulan] ?? ('Bulan ' . $lap->bulan);
                $detailItems = [];

                foreach ($lap->details as $d) {
                    $detailItems[] = [
                        'id'                 => $d->id,
                        'id_detail'          => $d->id,
                        'id_mapel'           => $d->id_mapel,
                        'nama_mapel'         => $d->mapel->nama ?? '-',
                        'materi'             => $d->materi,
                        'deskripsi_penilaian'=> $d->deskripsi_penilaian,
                        'minggu_ke'          => $d->minggu_ke,
                        'nama_ustadz'        => $d->pengampu->pegawai->nama ?? ($d->pengampu->name ?? '-'),
                    ];
                }

                $lapData = [
                    'id'         => $lap->id,
                    'bulan'      => $lap->bulan,
                    'nama_bulan' => $namaBulan,
                    'semester'   => $lap->semester,
                    'kelas'      => $lap->kelas,
                    'detail'     => $detailItems,
                ];

                if ($lap->semester == 1) {
                    $ganjil[$lap->bulan] = $lapData;
                } else {
                    $genap[$lap->bulan] = $lapData;
                }
            }

            ksort($ganjil);
            ksort($genap);

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'data'    => [
                    'santri' => [
                        'nama'     => $santri->namaSantri,
                        'no_induk' => $santri->noInduk,
                        'kelas'    => $santri->kodeKelas,
                        'jk'       => $santri->jenisKelamin,
                    ],
                    'ganjil' => $ganjil,
                    'genap'  => $genap,
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('KemadrasahanWebController@getDataSantri Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Gagal mengambil rekap nilai santri: ' . $e->getMessage(),
                'data'    => null
            ], 500);
        }
    }

    /**
     * AJAX: Mengambil 1 record detail penilaian untuk formulir edit.
     */
    public function getDetail($idDetail)
    {
        try {
            $currentUser = Auth::user();
            $detail = DetailPenilaianKemadrasahan::with(['laporan', 'mapel'])->findOrFail($idDetail);

            // RESTRICTION: Validasi hak akses kelas bagi ustadz
            $santriKelas = DB::table('santri_detail')->where('no_induk', $detail->laporan->no_induk)->value('kelas');
            if (!$this->checkClassAccess($santriKelas, $currentUser) && $detail->id_pengampu != $currentUser->id) {
                return response()->json([
                    'success' => false,
                    'status'  => 'error',
                    'message' => 'Akses ditolak: Anda tidak memiliki hak akses pada data penilaian ini.',
                    'data'    => null
                ], 403);
            }

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'data'    => [
                    'id'                  => $detail->id,
                    'id_laporan'          => $detail->id_laporan,
                    'id_mapel'            => $detail->id_mapel,
                    'minggu_ke'           => $detail->minggu_ke,
                    'materi'              => $detail->materi,
                    'deskripsi_penilaian' => $detail->deskripsi_penilaian,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Detail penilaian tidak ditemukan: ' . $e->getMessage(),
                'data'    => null
            ], 404);
        }
    }

    /**
     * AJAX: Menyimpan nilai (Single atau Bulk).
     * Menggunakan proteksi hak akses kelas ustadz.
     */
    public function store(Request $request)
    {
        $request->validate([
            'idUser'             => 'nullable|integer',
            'tipeInput'          => 'required|in:single,bulk',
            'bulan'              => 'required|integer|min:1|max:12',
            'semester'           => 'required|integer|in:1,2',
            'idMapel'            => 'required|integer|exists:ref_mapel,id',
            'mingguKe'           => 'required|integer|min:1|max:5',
            'materi'             => 'required|string',
            'deskripsiPenilaian' => 'required|string',
            'noInduk'            => 'required_if:tipeInput,single',
            'kodeKelas'          => 'nullable|string',
        ]);

        $currentUser = Auth::user();
        $isAdmin = $this->isAdmin($currentUser);

        // Jika ustadz, pastikan idPengampu adalah dirinya sendiri
        $idPengampu = $isAdmin ? ($request->idUser ?? $currentUser->id) : $currentUser->id;

        DB::beginTransaction();

        try {
            // Tentukan kode kelas
            $kodeKelas = $request->kodeKelas;

            if ($request->tipeInput === 'single') {
                $santri = Santri::where('no_induk', $request->noInduk)->first();
                if (!$santri) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Santri dengan No. Induk ' . $request->noInduk . ' tidak ditemukan.'
                    ], 404);
                }

                // RESTRICTION: Validasi hak akses ustadz terhadap santri ini
                if (!$this->checkClassAccess($santri->kelas, $currentUser)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Akses ditolak: Anda hanya dapat menginput nilai untuk santri di kelas yang Anda ampu.'
                    ], 403);
                }

                $tingkatKelas = substr($santri->kelas ?? $kodeKelas, 0, 1);

                $laporan = LaporanBulananKemadrasahan::updateOrCreate(
                    [
                        'no_induk' => $santri->no_induk,
                        'bulan'    => (int) $request->bulan,
                        'semester' => (int) $request->semester,
                        'kelas'    => $tingkatKelas,
                    ],
                    [
                        'updated_at' => now(),
                    ]
                );

                DetailPenilaianKemadrasahan::updateOrCreate(
                    [
                        'id_laporan' => $laporan->id,
                        'id_mapel'   => (int) $request->idMapel,
                        'minggu_ke'  => (int) $request->mingguKe,
                    ],
                    [
                        'id_pengampu'         => $idPengampu,
                        'materi'              => $request->materi,
                        'deskripsi_penilaian' => $request->deskripsiPenilaian,
                        'updated_at'          => now(),
                    ]
                );
            } else {
                // Mode BULK
                // Jika ustadz mencoba menginput kodeKelas yang bukan kelasnya, tolak akses
                if (!$isAdmin) {
                    if ($request->filled('kodeKelas') && !$this->checkClassAccess($request->kodeKelas, $currentUser)) {
                        DB::rollBack();
                        return response()->json([
                            'success' => false,
                            'message' => 'Akses ditolak: Anda hanya dapat menginput nilai massal untuk kelas yang Anda ampu.'
                        ], 403);
                    }

                    $assignedClass = DB::table('ref_kelas')
                        ->where('employee_id', $currentUser->pegawai_id)
                        ->value('code');
                    $kodeKelas = $assignedClass ?? $kodeKelas;
                }

                // RESTRICTION: Validasi hak akses kelas untuk mode bulk
                if (!$this->checkClassAccess($kodeKelas, $currentUser)) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Akses ditolak: Anda hanya dapat menginput nilai massal untuk kelas yang Anda ampu.'
                    ], 403);
                }

                $santriList = Santri::where('kelas', $kodeKelas)->get();
                if ($santriList->isEmpty()) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada santri yang terdaftar di kelas ' . strtoupper($kodeKelas) . '.'
                    ], 404);
                }

                $tingkatKelas = substr($kodeKelas, 0, 1);

                foreach ($santriList as $santri) {
                    $laporan = LaporanBulananKemadrasahan::updateOrCreate(
                        [
                            'no_induk' => $santri->no_induk,
                            'bulan'    => (int) $request->bulan,
                            'semester' => (int) $request->semester,
                            'kelas'    => $tingkatKelas,
                        ],
                        [
                            'updated_at' => now(),
                        ]
                    );

                    DetailPenilaianKemadrasahan::updateOrCreate(
                        [
                            'id_laporan' => $laporan->id,
                            'id_mapel'   => (int) $request->idMapel,
                            'minggu_ke'  => (int) $request->mingguKe,
                        ],
                        [
                            'id_pengampu'         => $idPengampu,
                            'materi'              => $request->materi,
                            'deskripsi_penilaian' => $request->deskripsiPenilaian,
                            'updated_at'          => now(),
                        ]
                    );
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'message' => $request->tipeInput === 'bulk'
                    ? 'Nilai berhasil disimpan ke seluruh santri di kelas ' . strtoupper($kodeKelas)
                    : 'Nilai santri berhasil disimpan.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('KemadrasahanWebController@store Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Gagal menyimpan data nilai: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX: Memperbarui record detail penilaian.
     * Menggunakan proteksi hak akses kelas ustadz.
     */
    public function update(Request $request, $idDetail)
    {
        $request->validate([
            'materi'             => 'required|string',
            'deskripsiPenilaian' => 'required|string',
            'mingguKe'           => 'nullable|integer|min:1|max:5',
            'idMapel'            => 'nullable|integer|exists:ref_mapel,id',
        ]);

        try {
            $currentUser = Auth::user();
            $detail = DetailPenilaianKemadrasahan::with('laporan')->findOrFail($idDetail);

            // RESTRICTION: Validasi hak akses kelas bagi ustadz
            $santriKelas = DB::table('santri_detail')->where('no_induk', $detail->laporan->no_induk)->value('kelas');
            if (!$this->checkClassAccess($santriKelas, $currentUser) && $detail->id_pengampu != $currentUser->id) {
                return response()->json([
                    'success' => false,
                    'status'  => 'error',
                    'message' => 'Akses ditolak: Anda tidak memiliki izin mengedit penilaian ini.'
                ], 403);
            }

            $updateData = [
                'materi'              => $request->materi,
                'deskripsi_penilaian' => $request->deskripsiPenilaian,
            ];

            if ($request->filled('mingguKe')) {
                $updateData['minggu_ke'] = (int) $request->mingguKe;
            }
            if ($request->filled('idMapel')) {
                $updateData['id_mapel'] = (int) $request->idMapel;
            }

            $detail->update($updateData);

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'message' => 'Data penilaian berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            Log::error('KemadrasahanWebController@update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Gagal memperbarui nilai: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * AJAX: Menghapus record detail penilaian.
     * Menggunakan proteksi hak akses kelas ustadz.
     */
    public function destroy($idDetail)
    {
        try {
            $currentUser = Auth::user();
            $detail = DetailPenilaianKemadrasahan::with('laporan')->findOrFail($idDetail);

            // RESTRICTION: Validasi hak akses kelas bagi ustadz
            $santriKelas = DB::table('santri_detail')->where('no_induk', $detail->laporan->no_induk)->value('kelas');
            if (!$this->checkClassAccess($santriKelas, $currentUser) && $detail->id_pengampu != $currentUser->id) {
                return response()->json([
                    'success' => false,
                    'status'  => 'error',
                    'message' => 'Akses ditolak: Anda tidak memiliki izin menghapus penilaian ini.'
                ], 403);
            }

            $idLaporan = $detail->id_laporan;
            $detail->delete();

            // Jika laporan bulanan tidak memiliki detail lagi, hapus laporan bulanan agar bersih
            $remaining = DetailPenilaianKemadrasahan::where('id_laporan', $idLaporan)->count();
            if ($remaining === 0) {
                LaporanBulananKemadrasahan::where('id', $idLaporan)->delete();
            }

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'message' => 'Data penilaian berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            Log::error('KemadrasahanWebController@destroy Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => 'Gagal menghapus data penilaian: ' . $e->getMessage()
            ], 500);
        }
    }
}
