@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <h4>Laporan Pengeluaran</h4>
        <br>
        <small>Periode {{ $date1 }} s/d {{ $date2 }}</small>
    </div>
    <br>
@endif
<table class="table table-hover">
    <thead>
        <tr>
            <th class="w-10px">No.</th>
            <th>Tanggal</th>
            <th>No. Bukti</th>
            <th>Deskripsi</th>
            <th>Jenis Pengeluaran</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($data as $index => $row)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $row->date }}</td>
                <td>{{ $row->receipt }}</td>
                <td>{{ $row->description }}</td>
                <td>{{ $row->expenditure_type }}</td>
                <td class="text-end">{{ number_format($row->cost) }}</td>
                @php
                    $total += $row->cost;
                @endphp
            </tr>
        @endforeach
        <tr>
            <th colspan="5">TOTAL</th>
            <th class="text-end">{{ number_format($total) }}</th>
        </tr>
    </tbody>
</table>
@if ($cetak)
    <div class="text-end">
        <small><small>Tgl. Cetak : {{ date('d-m-Y H:i:s') }}</small></small>
    </div>
@endif
