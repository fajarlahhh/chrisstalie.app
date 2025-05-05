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
            <th colspan="{{ collect($konsinyasi)->count()}}">Modal Konsinyasi</th>
            <th colspan="{{ collect($nakes)->count() + 1 }}">Pembagian</th>
        </tr>
        <tr>
            @foreach ($konsinyasi as $row)
                <th class="w-100px">{{ $row['nama'] }}</th>
            @endforeach
            @foreach ($nakes as $row)
                <th class="w-100px">{{ $row['nama'] }}</th>
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
                'nama' => $q[0]['nama'],
                'harga' => collect($q)->sum(fn($r) => $r['harga'] * $r['qty']),
                'discount' => collect($q)->sum(fn($r) => $r['discount'] * $r['qty']),
                'harga_discount' => collect($q)->sum(fn($r) => $r['harga_discount'] * $r['qty']),
                'qty' => collect($q)->sum('qty'),
                'modal' => collect($q)->sum(fn($r) => $r['modal'] * $r['qty']),
                'porsi_kantor' => collect($q)->sum(fn($r) => ($r['harga_discount']-$r['modal']) * $r['porsi_kantor']/100 * $r['qty']),
                'porsi_nakes' => collect($q)->sum(fn($r) => ($r['harga_discount']-$r['modal']) * $r['porsi_nakes']/100 * $r['qty']),
            ],
        )->values()->toArray() as $key => $row)
            <tr>
                <td>{{ ++$index }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="text-center">{{ number_format($row['qty']) }}</td>
                <td class="text-end">{{ number_format($row['harga']) }}</td>
                <td class="text-end">{{ number_format($row['discount']) }}</td>
                <td class="text-end">{{ number_format($row['harga_discount']) }}</td>
                @foreach ($konsinyasi as $subRow)
                    <td class="w-100px text-end">
                        {{ number_format(
                            collect($data)->where('id', $row['id'])->where('consignment_id', $subRow['id'])->sum(fn($r) => $r['modal'] * $r['qty']),
                        ) }}
                    </td>
                @endforeach
                @foreach ($nakes as $subRow)
                    <td class="w-100px text-end">
                        {{ number_format(
                            collect($data)->where('id', $row['id'])->where('nakes_id', $subRow['id'])->sum(fn($r) => ($r['harga_discount']-$r['modal']) * $r['porsi_nakes']/100 * $r['qty']),
                        ) }}
                    </td>
                @endforeach
                <td class="text-end">{{ number_format(collect($data)->where('id', $row['id'])->sum(fn($r) => ($r['harga_discount']-$r['modal']) * $r['porsi_kantor']/100 * $r['qty'])) }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">TOTAL</th>
            <th class="text-end">
                {{ number_format(collect($data)->sum('harga')) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum('discount')) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['harga'] - $row['discount'])) }}
            </th>
            @foreach ($konsinyasi as $subRow)
                <th class="w-100px text-end">
                    {{ number_format(
                        collect($data)->where('consignment_id', $subRow['id'])->sum(fn($r) => $r['modal'] * $r['qty']),
                    ) }}
                </th>
            @endforeach
            @foreach ($nakes as $subRow)
                <th class="w-100px text-end">
                    {{ number_format(
                        collect($data)->where('nakes_id', $subRow['id'])->sum(fn($r) => ($r['harga_discount']-$r['modal']) * $r['porsi_nakes']/100 * $r['qty']),
                    ) }}
                </th>
            @endforeach
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($r) => ($r['harga_discount']-$r['modal']) * $r['porsi_kantor']/100 * $r['qty'])) }}
            </th>
        </tr>
    </tbody>
</table>
@if ($cetak)
    <div class="text-end">
        <small><small>Tgl. Cetak : {{ date('d-m-Y H:i:s') }}</small></small>
    </div>
@endif
