<form method="POST" action="{{url('pembayaran/generate_tunggakan_single')}}">
    <table class="table">
      <tr>
        <td>No. Induk</td>
        <td>
          <input type="text" name="no_induk" class="form-control" value="{{$pembayaran->no_induk ?? $no_induk}}">
        </td>
      </tr>
      <tr>
        <td>Bulan</td>
        <td>
          <select name="bulan" class="form-control">
            @foreach($list_bulan as $key=>$value)
            <option value="{{$key}}" {{($key == ($pembayaran->bulan ?? date('m'))) ? "selected":""}}>{{$value}}</option>
            @endforeach
          </select>
        </td>
      </tr>
      <tr>
        <td>Tahun</td>
        <td>
            <input type="text" name="tahun" class="form-control" value="{{$pembayaran->tahun ?? date('Y')}}">
        </td>
      </tr>
      {{-- <tr>
        <td>Total Bayar (per santri)</td>
        <td>
          <input type="text" name="total_bayar" class="form-control" placeholder="0" onkeyup="splitInDots(this)">
        </td>
      </tr> --}}
      @php $total = 0; @endphp
      @foreach($jenis_pembayaran as $jenis_pembayaran)
        <tr>
            <td>{{$jenis_pembayaran->jenis}}<input type="hidden" name="id_jenis_pembayaran[]" value='{{ $jenis_pembayaran->id }}'></td>
            <td><input type="text" onkeyup="splitInDots2(this)" id="jenis_{{$jenis_pembayaran->id}}" placeholder="0" name="jenis_pembayaran[]" class="form-control" value="{{(!empty($detail_pembayaran[$jenis_pembayaran->id]->harga)) ? number_format($detail_pembayaran[$jenis_pembayaran->id]->harga,0,",",".") : '0'}}"></td>
        </tr>
      <tr>
        <td>Total Pembayaran</td>
        <td>
          <input type="text" class="form-control" name="total_bayar" id="total" value="{{number_format(($pembayaran->total_bayar ?? 0),0,"",".")}}" readonly>
        </td>
      </tr>
      <tr>
        <td colspan=2><button type="submit" class="btn btn-primary" value="Generate">Simpan</button</td>
      </tr>
    </table>

  </form>