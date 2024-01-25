<?php

namespace App\Exports;

use App\Models\PsbPesertaOnline;
use Maatwebsite\Excel\Concerns\FromCollection;

class PsbExport implements FromCollection
{
  /**
   * @return \Illuminate\Support\Collection
   */
  public function collection()
  {
    return PsbPesertaOnline::all();
  }
}
