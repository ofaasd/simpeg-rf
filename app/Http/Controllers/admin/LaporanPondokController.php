<?php

namespace App\Http\Controllers\admin;

use App\Models\Santri;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bangunan;
use App\Models\Barang;
use App\Models\DetailSantriTahfidz;
use App\Models\Elektronik;
use App\Models\EmployeeNew;
use App\Models\Pembayaran;
use App\Models\PsbPesertaOnline;
use App\Models\Ruang;
use App\Models\Tanah;
use App\Models\TbAlumniSantriDetail;
use App\Models\TbKesehatan;
use App\Models\TbPemeriksaan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanPondokController extends Controller
{
  public function index(Request $request)
  {
    $title = 'Laporan Pondok';

    if (!empty($request->input())) {
      $pilihanLaporan = $request->input('pilih-laporan');
      $periode = $request->input('pilih-periode');

      ini_set('pcre.backtrack_limit', 10000000);

      switch ($pilihanLaporan) {
        case 0:
          $var['santri'] = Santri::select(
            'santri_detail.no_induk',
            'santri_detail.nisn',
            'santri_detail.nik',
            'santri_detail.nama',
            'santri_detail.anak_ke',
            'santri_detail.tempat_lahir',
            'santri_detail.tanggal_lahir',
            'santri_detail.jenis_kelamin',
            'santri_detail.alamat',
            'santri_detail.kelurahan',
            'santri_detail.kecamatan',
            'santri_detail.kelas',
            'santri_detail.nama_lengkap_ayah',
            'santri_detail.pendidikan_ayah',
            'santri_detail.pekerjaan_ayah',
            'santri_detail.nama_lengkap_ibu',
            'santri_detail.pendidikan_ibu',
            'santri_detail.pekerjaan_ibu',
            'santri_detail.no_hp',
            'kota_kab_tbl.nama_kota_kab AS kabupatenKota',
            'provinces.prov_name AS namaProvinsi',
            'ref_kamar.name AS namaKamar',
            'ref_tahfidz.name AS kelasTahfidz'
          )
            ->leftJoin('kota_kab_tbl', 'kota_kab_tbl.id', 'santri_detail.kabkota')
            ->leftJoin('provinces', 'provinces.prov_id', 'santri_detail.provinsi')
            ->leftJoin('ref_kamar', 'ref_kamar.id', 'santri_detail.kamar_id')
            ->leftJoin('ref_tahfidz', 'ref_tahfidz.id', 'santri_detail.tahfidz_id')
            ->get();

          $var['staff'] = EmployeeNew::select(
            'employee_new.*',
            'employee_status_details.name as namaJabatan',
            'grades.name AS pendidikan'
          )
            ->leftJoin('employee_status_details', 'employee_status_details.id', 'employee_new.jabatan_new')
            ->leftJoin('grades', 'grades.id', 'employee_new.pendidikan')
            ->get();

          $bangunan = Bangunan::select(
            'aset_bangunan.*',
            'ref_gedung.nama AS namaGedung',
            'ref_lantai.nama AS nomorLantai',
            'aset_tanah.nama AS tanah'
          )
            ->leftJoin('ref_gedung', 'ref_gedung.kode', 'aset_bangunan.kode_gedung')
            ->leftJoin('ref_lantai', 'ref_lantai.id', 'aset_bangunan.id_lantai')
            ->leftJoin('aset_tanah', 'aset_tanah.kode', 'aset_bangunan.kode_tanah')
            ->get();

          $barang = Barang::select(
            'aset_barang.*',
            'aset_ruang.nama AS namaRuang',
            'ref_jenis_barang.nama AS jenisBarang'
          )
            ->leftJoin('aset_ruang', 'aset_ruang.kode', 'aset_barang.kode_ruang')
            ->leftJoin('ref_jenis_barang', 'ref_jenis_barang.kode', 'aset_barang.kode_jenis_barang')
            ->get();

          $elektronik = Elektronik::select(
              'aset_elektronik.*', 
              'aset_ruang.nama AS namaRuang',
              'ref_jenis_barang.nama AS jenisBarang'
              )
            ->leftJoin('aset_ruang', 'aset_ruang.kode', 'aset_elektronik.kode_ruang')
            ->leftJoin('ref_jenis_barang', 'ref_jenis_barang.kode', 'aset_elektronik.kode_jenis_barang')
            ->get();

          $ruang = Ruang::select(
            'aset_ruang.*',
            'ref_gedung.nama AS namaGedung',
            'ref_lantai.nama AS nomorLantai',
            'ref_jenis_ruang.nama AS jenisRuang'
          )
            ->leftJoin('ref_gedung', 'ref_gedung.kode', 'aset_ruang.kode_gedung')
            ->leftJoin('ref_lantai', 'ref_lantai.id', 'aset_ruang.id_lantai')
            ->leftJoin('ref_jenis_ruang', 'ref_jenis_ruang.kode', 'aset_ruang.kode_jenis_ruang')
            ->get();

          $tanah = Tanah::all();

          $var['aset'] = [
            'bangunan' => $bangunan,
            'barang' => $barang,
            'elektronik' => $elektronik,
            'ruang' => $ruang,
            'tanah' => $tanah,
          ];

          $var['psb'] = PsbPesertaOnline::select(
            'psb_peserta_online.*',
            'provinces.prov_name AS provinsi',
            'kota_kab_tbl.nama_kota_kab AS kotaKabupaten',
            'psb_gelombang.nama_gel AS gelombang',
            'kelurahan_tbl.nama_kelurahan AS namaKelurahan',
            'kecamatan_tbl.nama_kecamatan AS namaKecamatan'
          )
            ->leftJoin('provinces', 'provinces.prov_id', 'psb_peserta_online.prov_id')
            ->leftJoin('kota_kab_tbl', 'kota_kab_tbl.id', 'psb_peserta_online.kota_id')
            ->leftJoin('psb_gelombang', 'psb_gelombang.id', 'psb_peserta_online.gelombang_id')
            ->leftJoin('kelurahan_tbl', 'kelurahan_tbl.id', 'psb_peserta_online.kelurahan')
            ->leftJoin('kecamatan_tbl', 'kecamatan_tbl.id', 'psb_peserta_online.kecamatan')
            ->get();

          $var['alumni'] = TbAlumniSantriDetail::all();

          $payments = Pembayaran::select(
            DB::raw('MONTH(tb_pembayaran.tanggal_bayar) as month'),
            DB::raw('YEAR(tb_pembayaran.tanggal_bayar) as year'),
            DB::raw('SUM(CASE WHEN tb_pembayaran.validasi = 1 THEN tb_pembayaran.jumlah ELSE 0 END) as total_valid'),
            DB::raw('SUM(CASE WHEN tb_pembayaran.validasi = 0 THEN tb_pembayaran.jumlah ELSE 0 END) as total_invalid'),
            DB::raw('SUM(CASE WHEN tb_pembayaran.validasi = 1 THEN 1 ELSE 0 END) as pembayaranValid'),
            DB::raw('SUM(CASE WHEN tb_pembayaran.validasi = 0 THEN 1 ELSE 0 END) as pembayaranTidakValid'),
            DB::raw('SUM(CASE WHEN tb_pembayaran.jumlah_tunggakan > 0 AND NOT EXISTS (
                                        SELECT 1 FROM tb_pembayaran_tunggakan 
                                        WHERE tb_pembayaran_tunggakan.id_pembayaran = tb_pembayaran.id
                                     ) THEN 1 ELSE 0 END) as tunggakan')
          )
            ->whereYear('tb_pembayaran.tanggal_bayar', $periode) // Filter tahun saat ini
            ->groupBy(DB::raw('YEAR(tb_pembayaran.tanggal_bayar)'), DB::raw('MONTH(tb_pembayaran.tanggal_bayar)'))
            ->get();

          // Buat array default untuk bulan (1-12) dengan nilai 0
          $results = [];
          for ($month = 1; $month <= 12; $month++) {
            $results[$month] = [
              'month' => $month,
              'total_valid' => 0,
              'total_invalid' => 0,
              'pembayaranValid' => 0,
              'pembayaranTidakValid' => 0,
              'jumlahTunggakan' => 0,
            ];
          }

          // Isi data berdasarkan hasil query
          foreach ($payments as $payment) {
            $results[$payment->month] = [
              'month' => $payment->month,
              'total_valid' => $payment->total_valid,
              'total_invalid' => $payment->total_invalid,
              'pembayaranValid' => $payment->pembayaranValid,
              'pembayaranTidakValid' => $payment->pembayaranTidakValid,
              'jumlahTunggakan' => $payment->tunggakan,
            ];
          }

          // Hasil akhir
          $results = array_values($results);

          $var['keuanganValidasi'] = $results;

          $payments = Pembayaran::select(
            DB::raw('MONTH(tb_pembayaran.tanggal_bayar) as month'),
            DB::raw('YEAR(tb_pembayaran.tanggal_bayar) as year'),
            'santri_detail.nama_lengkap_ayah as namaWali'
          )
            ->join('santri_detail', 'santri_detail.no_induk', '=', 'tb_pembayaran.nama_santri') // Join dengan tabel santri_detail
            ->whereYear('tb_pembayaran.tanggal_bayar', $periode) // Filter tahun
            ->groupBy(
              DB::raw('YEAR(tb_pembayaran.tanggal_bayar)'),
              DB::raw('MONTH(tb_pembayaran.tanggal_bayar)'),
              'santri_detail.nama_lengkap_ayah'
            )
            ->get();

          // Memeriksa pembayaran pada 12 bulan
          $santriPayments = [];

          foreach ($payments as $payment) {
            $santriPayments[$payment->namaWali][$payment->month] = true;
          }

          // Menampilkan hasil dalam bentuk 12 bulan
          $result = [];
          foreach ($santriPayments as $namaWali => $bulanBayar) {
            $result[] = [
              'namaWali' => $namaWali,
              'pembayaran' => array_map(fn($month) => isset($bulanBayar[$month]), range(1, 12)), // Memeriksa setiap bulan
            ];
          }
          $var['cekLaporanPembayaran'] = $result;

          $santri = Santri::all();
          $var['kesehatan'] = TbKesehatan::all();
          $list_santri = [];
          foreach ($santri as $row) {
            $list_santri[$row->no_induk] = $row;
          }

          $var['kesehatanSantri'] = $list_santri;

          $grouppedSantri = Santri::select(
            'santri_detail.nama',
            'tb_pemeriksaan.tinggi_badan AS tinggiBadan',
            'tb_pemeriksaan.berat_badan AS beratBadan',
            'tb_pemeriksaan.lingkar_pinggul AS lingkarPinggul',
            'tb_pemeriksaan.lingkar_dada AS lingkarDada',
            'tb_pemeriksaan.kondisi_gigi AS kondisiGigi',
            'employee_new.nama AS namaMurroby'
          )
            ->leftJoin('tb_pemeriksaan', 'tb_pemeriksaan.no_induk', 'santri_detail.no_induk')
            ->leftJoin('ref_kelas', 'ref_kelas.code', 'santri_detail.kelas')
            ->leftJoin('employee_new', 'employee_new.id', 'ref_kelas.employee_id')
            ->get();

          // Kelompokkan berdasarkan namaMurroby (nama employee)
          $var['pemeriksaanSantri'] = $grouppedSantri->groupBy('namaMurroby');

          $var['title'] = 'Semua Jenis';
          break;
        case 1:
          $var['santri'] = Santri::select(
            'santri_detail.no_induk',
            'santri_detail.nisn',
            'santri_detail.nik',
            'santri_detail.nama',
            'santri_detail.anak_ke',
            'santri_detail.tempat_lahir',
            'santri_detail.tanggal_lahir',
            'santri_detail.jenis_kelamin',
            'santri_detail.alamat',
            'santri_detail.kelurahan',
            'santri_detail.kecamatan',
            'santri_detail.kelas',
            'santri_detail.nama_lengkap_ayah',
            'santri_detail.pendidikan_ayah',
            'santri_detail.pekerjaan_ayah',
            'santri_detail.nama_lengkap_ibu',
            'santri_detail.pendidikan_ibu',
            'santri_detail.pekerjaan_ibu',
            'santri_detail.no_hp',
            'kota_kab_tbl.nama_kota_kab AS kabupatenKota',
            'provinces.prov_name AS namaProvinsi',
            'ref_kamar.name AS namaKamar',
            'ref_tahfidz.name AS kelasTahfidz'
          )
            ->leftJoin('kota_kab_tbl', 'kota_kab_tbl.id', 'santri_detail.kabkota')
            ->leftJoin('provinces', 'provinces.prov_id', 'santri_detail.provinsi')
            ->leftJoin('ref_kamar', 'ref_kamar.id', 'santri_detail.kamar_id')
            ->leftJoin('ref_tahfidz', 'ref_tahfidz.id', 'santri_detail.tahfidz_id')
            ->get();
          $var['title'] = 'Santri';

          break;
        case 2:
          $var['staff'] = EmployeeNew::select(
            'employee_new.*',
            'employee_status_details.name as namaJabatan',
            'grades.name AS pendidikan'
          )
            ->leftJoin('employee_status_details', 'employee_status_details.id', 'employee_new.jabatan_new')
            ->leftJoin('grades', 'grades.id', 'employee_new.pendidikan')
            ->get();

          $var['title'] = 'Staff';
          break;
        case 3:
          $bangunan = Bangunan::select(
            'aset_bangunan.*',
            'ref_gedung.nama AS namaGedung',
            'ref_lantai.nama AS nomorLantai',
            'aset_tanah.nama AS tanah'
          )
            ->leftJoin('ref_gedung', 'ref_gedung.kode', 'aset_bangunan.kode_gedung')
            ->leftJoin('ref_lantai', 'ref_lantai.id', 'aset_bangunan.id_lantai')
            ->leftJoin('aset_tanah', 'aset_tanah.kode', 'aset_bangunan.kode_tanah')
            ->get();

            $barang = Barang::select(
            'aset_barang.*',
            'aset_ruang.nama AS namaRuang',
            'ref_jenis_barang.nama AS jenisBarang'
          )
            ->leftJoin('aset_ruang', 'aset_ruang.kode', 'aset_barang.kode_ruang')
            ->leftJoin('ref_jenis_barang', 'ref_jenis_barang.kode', 'aset_barang.kode_jenis_barang')
            ->get();

            $elektronik = Elektronik::select(
              'aset_elektronik.*', 
              'aset_ruang.nama AS namaRuang',
              'ref_jenis_barang.nama AS jenisBarang'
              )
            ->leftJoin('aset_ruang', 'aset_ruang.kode', 'aset_elektronik.kode_ruang')
            ->leftJoin('ref_jenis_barang', 'ref_jenis_barang.kode', 'aset_elektronik.kode_jenis_barang')
            ->get();

          $ruang = Ruang::select(
            'aset_ruang.*',
            'ref_gedung.nama AS namaGedung',
            'ref_lantai.nama AS nomorLantai',
            'ref_jenis_ruang.nama AS jenisRuang'
          )
          ->leftJoin('ref_gedung', 'ref_gedung.kode', 'aset_ruang.kode_gedung')
          ->leftJoin('ref_lantai', 'ref_lantai.id', 'aset_ruang.id_lantai')
          ->leftJoin('ref_jenis_ruang', 'ref_jenis_ruang.kode', 'aset_ruang.kode_jenis_ruang')
          ->get();

          $tanah = Tanah::all();

          $var['aset'] = [
            'bangunan' => $bangunan,
            'barang' => $barang,
            'elektronik' => $elektronik,
            'ruang' => $ruang,
            'tanah' => $tanah,
          ];
          $var['title'] = 'Aset';

          break;

        case 4:
          $payments = Pembayaran::select(
            DB::raw('MONTH(tb_pembayaran.tanggal_bayar) as month'),
            DB::raw('YEAR(tb_pembayaran.tanggal_bayar) as year'),
            DB::raw('SUM(CASE WHEN tb_pembayaran.validasi = 1 THEN tb_pembayaran.jumlah ELSE 0 END) as total_valid'),
            DB::raw('SUM(CASE WHEN tb_pembayaran.validasi = 0 THEN tb_pembayaran.jumlah ELSE 0 END) as total_invalid'),
            DB::raw('SUM(CASE WHEN tb_pembayaran.validasi = 1 THEN 1 ELSE 0 END) as pembayaranValid'),
            DB::raw('SUM(CASE WHEN tb_pembayaran.validasi = 0 THEN 1 ELSE 0 END) as pembayaranTidakValid'),
            DB::raw('SUM(CASE WHEN tb_pembayaran.jumlah_tunggakan > 0 AND NOT EXISTS (
                                    SELECT 1 FROM tb_pembayaran_tunggakan 
                                    WHERE tb_pembayaran_tunggakan.id_pembayaran = tb_pembayaran.id
                                 ) THEN 1 ELSE 0 END) as tunggakan')
          )
            ->whereYear('tb_pembayaran.tanggal_bayar', $periode) // Filter tahun saat ini
            ->groupBy(DB::raw('YEAR(tb_pembayaran.tanggal_bayar)'), DB::raw('MONTH(tb_pembayaran.tanggal_bayar)'))
            ->get();

          // Buat array default untuk bulan (1-12) dengan nilai 0
          $results = [];
          for ($month = 1; $month <= 12; $month++) {
            $results[$month] = [
              'month' => $month,
              'total_valid' => 0,
              'total_invalid' => 0,
              'pembayaranValid' => 0,
              'pembayaranTidakValid' => 0,
              'jumlahTunggakan' => 0,
            ];
          }

          // Isi data berdasarkan hasil query
          foreach ($payments as $payment) {
            $results[$payment->month] = [
              'month' => $payment->month,
              'total_valid' => $payment->total_valid,
              'total_invalid' => $payment->total_invalid,
              'pembayaranValid' => $payment->pembayaranValid,
              'pembayaranTidakValid' => $payment->pembayaranTidakValid,
              'jumlahTunggakan' => $payment->tunggakan,
            ];
          }

          // Hasil akhir
          $results = array_values($results);

          $var['keuanganValidasi'] = $results;

          $payments = Pembayaran::select(
            DB::raw('MONTH(tb_pembayaran.tanggal_bayar) as month'),
            DB::raw('YEAR(tb_pembayaran.tanggal_bayar) as year'),
            'santri_detail.nama_lengkap_ayah as namaWali'
          )
            ->join('santri_detail', 'santri_detail.no_induk', '=', 'tb_pembayaran.nama_santri') // Join dengan tabel santri_detail
            ->whereYear('tb_pembayaran.tanggal_bayar', $periode) // Filter tahun
            ->groupBy(
              DB::raw('YEAR(tb_pembayaran.tanggal_bayar)'),
              DB::raw('MONTH(tb_pembayaran.tanggal_bayar)'),
              'santri_detail.nama_lengkap_ayah'
            )
            ->get();

          // Memeriksa pembayaran pada 12 bulan
          $santriPayments = [];

          foreach ($payments as $payment) {
            $santriPayments[$payment->namaWali][$payment->month] = true;
          }

          // Menampilkan hasil dalam bentuk 12 bulan
          $result = [];
          foreach ($santriPayments as $namaWali => $bulanBayar) {
            $result[] = [
              'namaWali' => $namaWali,
              'pembayaran' => array_map(fn($month) => isset($bulanBayar[$month]), range(1, 12)), // Memeriksa setiap bulan
            ];
          }
          $var['cekLaporanPembayaran'] = $result;

          $var['title'] = 'Laporan Pembayaran';
          break;
        case 5:
          $var['psb'] = PsbPesertaOnline::select(
            'psb_peserta_online.*',
            'provinces.prov_name AS provinsi',
            'kota_kab_tbl.nama_kota_kab AS kotaKabupaten',
            'psb_gelombang.nama_gel AS gelombang',
            'kelurahan_tbl.nama_kelurahan AS namaKelurahan',
            'kecamatan_tbl.nama_kecamatan AS namaKecamatan'
          )
            ->leftJoin('provinces', 'provinces.prov_id', 'psb_peserta_online.prov_id')
            ->leftJoin('kota_kab_tbl', 'kota_kab_tbl.id', 'psb_peserta_online.kota_id')
            ->leftJoin('psb_gelombang', 'psb_gelombang.id', 'psb_peserta_online.gelombang_id')
            ->leftJoin('kelurahan_tbl', 'kelurahan_tbl.id', 'psb_peserta_online.kelurahan')
            ->leftJoin('kecamatan_tbl', 'kecamatan_tbl.id', 'psb_peserta_online.kecamatan')
            ->get();
          $var['title'] = 'Penerimaan Santri baru';

          break;

        case 6:
          $var['alumni'] = TbAlumniSantriDetail::all();
          $var['title'] = 'Alumni';
          break;

        case 7:
          $grouppedSantri = Santri::select(
            'santri_detail.nama',
            'tb_pemeriksaan.tinggi_badan AS tinggiBadan',
            'tb_pemeriksaan.berat_badan AS beratBadan',
            'tb_pemeriksaan.lingkar_pinggul AS lingkarPinggul',
            'tb_pemeriksaan.lingkar_dada AS lingkarDada',
            'tb_pemeriksaan.kondisi_gigi AS kondisiGigi',
            'employee_new.nama AS namaMurroby'
          )
            ->leftJoin('tb_pemeriksaan', 'tb_pemeriksaan.no_induk', 'santri_detail.no_induk')
            ->leftJoin('ref_kelas', 'ref_kelas.code', 'santri_detail.kelas')
            ->leftJoin('employee_new', 'employee_new.id', 'ref_kelas.employee_id')
            ->get();

          $var['pemeriksaanSantri'] = $grouppedSantri->groupBy('namaMurroby');

          $var['title'] = 'Pemeriksaan Santri';
          break;

        case 8:
          $santri = Santri::all();
          $var['kesehatan'] = TbKesehatan::all();
          $list_santri = [];
          foreach ($santri as $row) {
            $list_santri[$row->no_induk] = $row;
          }
          $var['kesehatanSantri'] = $list_santri;
          $var['title'] = 'Kesehatan Santri';
          break;
        default:
          return 'data tidak ditemukan';
      }
      $mpdf = new \Mpdf\Mpdf([
        'default_font' => 'times',
        'tempDir' => public_path('temp'),
        'format' => 'A4', // Format default A4 (portrait)
        'margin_top' => 15,
        'margin_right' => 15,
        'margin_bottom' => 15,
        'margin_left' => 15,
        'simpleTables' => true,
      ]);

      // Render HTML dari view
      $html = view('admin.laporan.laporan-pondok.laporan-all', $var)->render();

      // Menghilangkan XML declaration jika ada
      $html = preg_replace('/<\?xml[^>]*\?>/', '', $html);

      // Tulis halaman pertama (portrait) untuk semua konten sebelum bagian santri
      $beforeSantri = substr($html, 0, strpos($html, '<section id="santri">'));
      $mpdf->WriteHTML($beforeSantri);

      // Cek apakah ada bagian santri
      $santriPos = strpos($html, '<section id="santri">');
      if ($santriPos !== false) {
        // Tambahkan halaman baru dengan orientasi landscape untuk santri
        $mpdf->AddPage('L');

        // Ambil dan tulis bagian HTML untuk santri
        $santriHtml = substr($html, $santriPos, strpos($html, '</section>', $santriPos) - $santriPos + 10); // Ambil hingga end tag </section>
        $mpdf->WriteHTML($santriHtml);

        // Tambahkan halaman baru untuk bagian setelah santri jika ada
        $htmlAfterSantri = substr($html, $santriPos + strlen($santriHtml));
        if (!empty($htmlAfterSantri)) {
          $mpdf->AddPage('P'); // Kembali ke portrait
          $mpdf->WriteHTML($htmlAfterSantri);
        }
      } else {
        // Jika tidak ditemukan bagian santri, tulis semua konten dalam portrait
        $mpdf->WriteHTML($html);
      }

      // Output PDF
      $mpdf->Output("Laporan Akhir Tahun $periode " . $var['title'], \Mpdf\Output\Destination::INLINE);
    } else {
      $pilihLaporan = [
        0 => '',
        1 => 'Data Santri',
        'Data Staff',
        'Data Aset',
        'Data Keuangan',
        'Data PSB',
        'Data Alumni',
        'Data Pemeriksaan',
        'Data Kesehatan',
      ];

      $tahunPembayaran = Pembayaran::select(DB::raw('YEAR(tanggal_bayar) as year'))
        ->whereNotNull('tanggal_bayar') // Pastikan tanggal_bayar tidak null
        ->where('tanggal_bayar', '!=', '0000-00-00') // Pastikan tanggal_bayar bukan '0000-00-00'
        ->groupBy(DB::raw('YEAR(tanggal_bayar)'))
        ->orderByDesc('year') // Urutkan berdasarkan tahun
        ->get()
        ->pluck('year');

      $pilihPeriode = $tahunPembayaran
        ->mapWithKeys(function ($year) {
          return [$year => $year];
        })
        ->toArray();

      $var['pilihanLaporan'] = $pilihLaporan;
      $var['pilihanPeriode'] = $pilihPeriode;

      $data = '';
      return view('admin.laporan.laporan-pondok.index', compact('data', 'var', 'title'));
    }
  }
}
