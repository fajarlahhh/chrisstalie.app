@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <h4>Laporan Pembagian Penjualan</h4>
        <br>
        <small>Periode {{ $date1 }} s/d {{ $date2 }}</small>
    </div>
    <br>
@endif
<table class="table table-bordered fs-10">
    <thead>
        <tr>
            <th rowspan="2" class="w-10px">No.</th>
            <th rowspan="2">Barang</th>
            <th rowspan="2" class="w-70px">Qty</th>
            <th rowspan="2" class="w-100px">Jumlah Harga</th>
            <th rowspan="2" class="w-100px">Jumlah Diskon</th>
            <th rowspan="2" class="w-100px">Jumlah Penerimaan</th>
            <th colspan="{{ collect($consignment)->count()}}">Modal Konsinyasi</th>
            <th colspan="{{ collect($practitioner)->count() + 1 }}">Pembagian</th>
        </tr>
        <tr>
            @foreach ($consignment as $row)
                <th class="w-100px">{{ $row['name'] }}</th>
            @endforeach
            @foreach ($practitioner as $row)
                <th class="w-100px">{{ $row['name'] }}</th>
            @endforeach
            <th class="w-100px">Apotek</th>
        </tr>
    </thead>
    <tbody>
        @php
            $index = 0;
        @endphp
        @foreach (collect($data)->groupBy('id')->map(
            fn($q) => [
                'id' => $q[0]['id'],
                'name' => $q[0]['name'],
                'price' => collect($q)->sum(fn($r) => $r['price'] * $r['qty']),
                'discount' => collect($q)->sum(fn($r) => $r['discount'] * $r['qty']),
                'price_discount' => collect($q)->sum(fn($r) => $r['price_discount'] * $r['qty']),
                'qty' => collect($q)->sum('qty'),
                'capital' => collect($q)->sum(fn($r) => $r['capital'] * $r['qty']),
                'office_portion' => collect($q)->sum(fn($r) => ($r['price_discount']-$r['capital']) * $r['office_portion']/100 * $r['qty']),
                'practitioner_portion' => collect($q)->sum(fn($r) => ($r['price_discount']-$r['capital']) * $r['practitioner_portion']/100 * $r['qty']),
            ],
        )->values()->toArray() as $key => $row)
            <tr>
                <td>{{ ++$index }}</td>
                <td>{{ $row['name'] }}</td>
                <td class="text-center">{{ number_format($row['qty']) }}</td>
                <td class="text-end">{{ number_format($row['price']) }}</td>
                <td class="text-end">{{ number_format($row['discount']) }}</td>
                <td class="text-end">{{ number_format($row['price_discount']) }}</td>
                @foreach ($consignment as $subRow)
                    <td class="w-100px text-end">
                        {{ number_format(
                            collect($data)->where('id', $row['id'])->where('consignment_id', $subRow['id'])->sum(fn($r) => $r['capital'] * $r['qty']),
                        ) }}
                    </td>
                @endforeach
                @foreach ($practitioner as $subRow)
                    <td class="w-100px text-end">
                        {{ number_format(
                            collect($data)->where('id', $row['id'])->where('practitioner_id', $subRow['id'])->sum(fn($r) => ($r['price_discount']-$r['capital']) * $r['practitioner_portion']/100 * $r['qty']),
                        ) }}
                    </td>
                @endforeach
                <td class="text-end">{{ number_format(collect($data)->where('id', $row['id'])->sum(fn($r) => ($r['price_discount']-$r['capital']) * $r['office_portion']/100 * $r['qty'])) }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">TOTAL</th>
            <th class="text-end">
                {{ number_format(collect($data)->sum('price')) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum('discount')) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['price'] - $row['discount'])) }}
            </th>
            @foreach ($consignment as $subRow)
                <th class="w-100px text-end">
                    {{ number_format(
                        collect($data)->where('consignment_id', $subRow['id'])->sum(fn($r) => $r['capital'] * $r['qty']),
                    ) }}
                </th>
            @endforeach
            @foreach ($practitioner as $subRow)
                <th class="w-100px text-end">
                    {{ number_format(
                        collect($data)->where('practitioner_id', $subRow['id'])->sum(fn($r) => ($r['price_discount']-$r['capital']) * $r['practitioner_portion']/100 * $r['qty']),
                    ) }}
                </th>
            @endforeach
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($r) => ($r['price_discount']-$r['capital']) * $r['office_portion']/100 * $r['qty'])) }}
            </th>
        </tr>
    </tbody>
</table>
@if ($cetak)
    <div class="text-end">
        <small><small>Tgl. Cetak : {{ date('d-m-Y H:i:s') }}</small></small>
    </div>
@endif
