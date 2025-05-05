@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <h4>Laporan Jasa Pelayanan</h4>
            <br>
            <small>Periode {{ $date1 }} s/d {{ $date2 }}</small>
    </div>
    <br>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th rowspan="2" class="w-10px">No.</th>
            <th rowspan="2">Tindakan</th>
            <th rowspan="2" class="w-70px">Qty</th>
            <th rowspan="2" class="w-100px">Jumlah Harga</th>
            <th rowspan="2" class="w-100px">Jumlah Diskon</th>
            <th rowspan="2" class="w-100px">Jumlah Modal</th>
            <th rowspan="2" class="w-100px">Jumlah Keuntungan</th>
            <th colspan="{{ $nakes->count() + 1 }}">Pembagian</th>
        </tr>
        <tr>
            <th class="w-100px">Klinik</th>
            @foreach ($nakes as $row)
                <th class="w-100px">{{ $row['nama'] }}</th>
            @endforeach
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
                'harga' => collect($q)->sum('harga'),
                'discount' => collect($q)->sum('discount'),
                'qty' => collect($q)->sum('qty'),
                'keuntungan' => collect($q)->sum('keuntungan'),
                'modal' => collect($q)->sum('modal'),
                'porsi_kantor' => collect($q)->sum('porsi_kantor'),
            ],
        )->values()->toArray() as $key => $row)
            <tr>
                <td>{{ ++$index }}</td>
                <td>{{ $row['nama'] }}</td>
                <td class="text-center">{{ number_format($row['qty']) }}</td>
                <td class="text-end">{{ number_format($row['harga']) }}</td>
                <td class="text-end">{{ number_format($row['discount']) }}</td>
                <td class="text-end">{{ number_format($row['modal']) }}</td>
                <td class="text-end">{{ number_format($row['keuntungan']) }}</td>
                <td class="text-end">{{ number_format($row['porsi_kantor']) }}</td>
                @foreach ($nakes as $subRow)
                    <td class="text-end">
                        @php
                            $portion1 = collect($data)
                                ->where('id', $row['id'])
                                ->where('nakes_id', $subRow['id'])
                                ->sum(fn($q) => $q['porsi_nakes']);
                            $portion2 = collect($data)
                                ->where('id', $row['id'])
                                ->where('beautician_id', $subRow['id'])
                                ->sum(fn($q) => $q['upah_petugas']);
                        @endphp
                        {{ number_format($portion1 + $portion2) }}
                    </td>
                @endforeach
            </tr>
        @endforeach
        <tr>
            <th colspan="3">Total</th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['harga'])) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['discount'])) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['modal'])) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['keuntungan'])) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['porsi_kantor'])) }}
            </th>
            @foreach ($nakes as $subRow)
                <th class="text-end">
                    @php
                        $portion1 = collect($data)
                            ->where('nakes_id', $subRow['id'])
                            ->sum(fn($q) => $q['porsi_nakes']);
                        $portion2 = collect($data)
                            ->where('beautician_id', $subRow['id'])
                            ->sum(fn($q) => $q['upah_petugas']);
                    @endphp
                    {{ number_format($portion1 + $portion2) }}
                </th>
            @endforeach
        </tr>
    </tbody>
</table>
@if ($cetak)
    <div class="text-end">
        <small><small>Tgl. Cetak : {{ date('d-m-Y H:i:s') }}</small></small>
    </div>
@endif
