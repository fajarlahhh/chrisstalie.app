@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <h4>Laporan Harian Kas</h4>
        <br>
        <small>Periode {{ $date1 }} s/d {{ $date2 }}</small>
    </div>
    <br>
@endif
<table class="table table-hover">
    <tbody>
        <tr>
            <th colspan="2">Penerimaan Klinik</th>
            <th class="text-end">{{ number_format($data['Penerimaan Klinik']->sum(fn($q) => $q->amount + $q->admin)) }}</th>
        </tr>
        <tr>
            <th colspan="2">Penerimaan Apotek</th>
            <th class="text-end">{{ number_format($data['Penerimaan Apotek']->sum(fn($q) => $q->amount + $q->power_fee + $q->receipt_fee)) }}</th>
        </tr>
        <tr>
            <th colspan="3">Pengeluaran</th>
        </tr>
        @foreach ($data['Pengeluaran'] as $index => $detail)
            <tr>
                <td class="w-10px">{{ $index + 1 }}</td>
                <td>{{ $detail->uraian }}</td>
                <td class="text-end">{{ number_format($detail->cost) }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="2">TOTAL</th>
            <th class="text-end">{{ number_format($data['Pengeluaran']->sum('cost')) }}</th>
        </tr>
        <tr>
            <th colspan="2">SISA</th>
            <th class="text-end">{{ number_format($data['Penerimaan Klinik']->sum(fn($q) => $q->amount + $q->admin) + $data['Penerimaan Apotek']->sum(fn($q) => $q->amount + $q->power_fee + $q->receipt_fee) - $data['Pengeluaran']->sum('cost')) }}</th>
        </tr>
    </tbody>
</table>
@if ($cetak)
    <div class="text-end">
        <small><small>Tgl. Cetak : {{ date('d-m-Y H:i:s') }}</small></small>
    </div>
@endif
