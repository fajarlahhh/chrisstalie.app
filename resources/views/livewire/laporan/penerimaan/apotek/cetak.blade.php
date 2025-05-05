@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <h4>Laporan Penerimaan Apotek</h4>
            <br>
            <small>Periode {{ $date1 }} s/d {{ $date2 }}</small>
    </div>
    <br>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Barang</th>
            <th>Konsinyasi</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $index = 0;
        @endphp
        @foreach ($data as $i => $row)
            <tr>
                <td>{{ ++$index }}</td>
                <td>{{ $row['nama'] }}</td>
                <td>{{ $row['konsinyasi'] }}</td>
                <td>{{ $row['satuan'] }}</td>
                <td class="text-end">{{ number_format($row['harga_discount'], 2) }}</td>
                <td class="text-end">{{ number_format($row['qty'], 2) }}</td>
                <td class="text-end">{{ number_format($row['total'], 2) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="6" class="text-end">By. Admin</td>
            <td class="text-end">{{ number_format($admin->sum(fn($q) => $q->power_fee + $q->receipt_fee), 2) }}</td>
        </tr>
        <tr>
            <th colspan="6" class="text-end">Total</th>
            <th class="text-end">{{ number_format(collect($data)->sum('total') + $admin->sum(fn($q) => $q->power_fee + $q->receipt_fee), 2) }}</th>
        </tr>
    </tbody>
</table>
@if ($cetak)
    <div class="text-end">
        <small><small>Tgl. Cetak : {{ date('d-m-Y H:i:s') }}</small></small>
    </div>
@endif
