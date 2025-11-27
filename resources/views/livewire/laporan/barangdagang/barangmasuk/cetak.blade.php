@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Laporan Barang Masuk</h5>
        <hr>
    </div>
    <br>
    <table> 
        <tr>
            <th class="w-100px">Periode</th>
            <th class="w-10px">:</th>
            <td>{{ $bulan }}</td>
        </tr>
        <tr>
            <th>Kategori</th>
            <th class="w-10px">:</th>
            <td>{{ $kategori }}</td>
        </tr>
    </table>
@endif
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th class="bg-gray-300 text-white">No.</th>
            <th class="bg-gray-300 text-white">Tanggal</th>
            <th class="bg-gray-300 text-white">Uraian</th>
            <th class="bg-gray-300 text-white">Barang</th>
            <th class="bg-gray-300 text-white">Satuan</th>
            <th class="bg-gray-300 text-white">No. Batch</th>
            <th class="bg-gray-300 text-white">Tanggal Kedaluarsa</th>
            <th class="bg-gray-300 text-white">Supplier</th>
            <th class="bg-gray-300 text-white">Harga Beli</th>
            <th class="bg-gray-300 text-white">Qty</th>
            <th class="bg-gray-300 text-white">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td nowrap>{{ $row['tanggal'] }}</td>
                <td nowrap>{{ $row['uraian'] }}</td>
                <td nowrap>{{ $row['barang'] }}</td>
                <td nowrap>{{ $row['satuan'] }}</td>
                <td nowrap>{{ $row['no_batch'] }}</td>
                <td nowrap>{{ $row['tanggal_kedaluarsa'] }}</td>
                <td nowrap>{{ $row['supplier'] }}</td>
                <td nowrap class="text-end">{{ number_format($row['harga_beli']) }}</td>
                <td nowrap class="text-end">{{ number_format($row['qty']) }}</td>
                <td nowrap class="text-end">{{ number_format($row['total']) }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="10" class="text-end">Total</th>
            <th class="text-end">{{ number_format(collect($data)->sum('total')) }}</th>
        </tr>
    </tbody>
</table>
