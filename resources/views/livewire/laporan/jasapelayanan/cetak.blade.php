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
            <th colspan="{{ $practitioner->count() + 1 }}">Pembagian</th>
        </tr>
        <tr>
            <th class="w-100px">Klinik</th>
            @foreach ($practitioner as $row)
                <th class="w-100px">{{ $row['name'] }}</th>
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
                'name' => $q[0]['name'],
                'price' => collect($q)->sum('price'),
                'discount' => collect($q)->sum('discount'),
                'qty' => collect($q)->sum('qty'),
                'profit' => collect($q)->sum('profit'),
                'capital' => collect($q)->sum('capital'),
                'office_portion' => collect($q)->sum('office_portion'),
            ],
        )->values()->toArray() as $key => $row)
            <tr>
                <td>{{ ++$index }}</td>
                <td>{{ $row['name'] }}</td>
                <td class="text-center">{{ number_format($row['qty']) }}</td>
                <td class="text-end">{{ number_format($row['price']) }}</td>
                <td class="text-end">{{ number_format($row['discount']) }}</td>
                <td class="text-end">{{ number_format($row['capital']) }}</td>
                <td class="text-end">{{ number_format($row['profit']) }}</td>
                <td class="text-end">{{ number_format($row['office_portion']) }}</td>
                @foreach ($practitioner as $subRow)
                    <td class="text-end">
                        @php
                            $portion1 = collect($data)
                                ->where('id', $row['id'])
                                ->where('practitioner_id', $subRow['id'])
                                ->sum(fn($q) => $q['practitioner_portion']);
                            $portion2 = collect($data)
                                ->where('id', $row['id'])
                                ->where('beautician_id', $subRow['id'])
                                ->sum(fn($q) => $q['beautician_fee']);
                        @endphp
                        {{ number_format($portion1 + $portion2) }}
                    </td>
                @endforeach
            </tr>
        @endforeach
        <tr>
            <th colspan="3">Total</th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['price'])) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['discount'])) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['capital'])) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['profit'])) }}
            </th>
            <th class="text-end">
                {{ number_format(collect($data)->sum(fn($row) => $row['office_portion'])) }}
            </th>
            @foreach ($practitioner as $subRow)
                <th class="text-end">
                    @php
                        $portion1 = collect($data)
                            ->where('practitioner_id', $subRow['id'])
                            ->sum(fn($q) => $q['practitioner_portion']);
                        $portion2 = collect($data)
                            ->where('beautician_id', $subRow['id'])
                            ->sum(fn($q) => $q['beautician_fee']);
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
