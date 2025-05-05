@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <h4>Laporan Pengeluaran Gaji</h4>
        <br>
        <small>Periode {{ $date1 }} s/d {{ $date2 }}</small>
    </div>
    <br>
@endif
<table class="table table-hover">
    <thead>
        <tr>
            <th class="w-10px">No.</th>
            <th>Pegawai</th>
            <th>Deskripsi</th>
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
                <td colspan="3">{{ $row->pegawai->nama }}</td>
            </tr>
            @foreach ($row->expenditureDetail as $detail)
                <tr>
                    <td colspan="2"></td>
                    <td>{{ str_replace(['+ ', '- '], '', $detail->uraian) }}</td>
                    <td class="text-end">{{ number_format($detail->cost) }}</td>
                </tr>
                @php
                    $total += $detail->cost;
                @endphp
            @endforeach
        @endforeach
        <tr>
            <th colspan="3">TOTAL</th>
            <th class="text-end">{{ number_format($total) }}</th>
        </tr>
    </tbody>
</table>
@if ($cetak)
    <div class="text-end">
        <small><small>Tgl. Cetak : {{ date('d-m-Y H:i:s') }}</small></small>
    </div>
@endif
