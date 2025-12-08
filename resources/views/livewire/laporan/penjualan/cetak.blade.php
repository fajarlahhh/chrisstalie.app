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
            <td>{{ $tanggal1 }} s/d {{ $tanggal2 }}</td>
        </tr>
    </table>
@endif
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th class="bg-gray-300 text-white">No.</th>
            <th class="bg-gray-300 text-white">Tanggal</th>
            <th class="bg-gray-300 text-white">Barang</th>
            <th class="bg-gray-300 text-white">Satuan</th>
            <th class="bg-gray-300 text-white">Harga Jual</th>
            <th class="bg-gray-300 text-white">Qty</th>
            <th class="bg-gray-300 text-white">Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($data as $key => $row)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td nowrap>{{ $row->tanggal }}</td>
                <td nowrap>{{ $row->barang->nama }}</td>
                <td nowrap>{{ $row->barangSatuan->nama }}</td>
                <td nowrap class="text-end">{{ number_format($row->harga, 2) }}</td>
                <td nowrap class="text-end">{{ $row->qty }}</td>
                <td nowrap class="text-end">
                    {{ number_format($row->harga * $row->qty, 2) }}</td>
                @php
                    $total += $row->harga * $row->qty;
                @endphp
            </tr>
        @endforeach
        <tr>
            <th colspan="6" class="text-end">Total</th>
            <th class="text-end">{{ number_format($total, 2) }}
            </th>
        </tr>
    </tbody>
</table>
