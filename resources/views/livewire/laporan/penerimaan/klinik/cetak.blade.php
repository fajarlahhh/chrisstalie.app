@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <h4>Laporan Penerimaan Klinik ({{ $type }})</h4>
        <br>
        <small>Periode {{ $date1 }} s/d {{ $date2 }}</small>
    </div>
    <br>
@endif
@if ($type == 'Rekap')
    <table class="table table-bordered fs-10">
        <thead>
            <tr>
                <th rowspan="2" class="w-10px">No.</th>
                <th rowspan="2">Tindakan</th>
                <th rowspan="2" class="w-70px">Qty</th>
                <th rowspan="2" class="w-100px">Jumlah Harga</th>
                <th rowspan="2" class="w-100px">Jumlah Harga Setelah Diskon</th>
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
                'priceAfterDiscount' => collect($q)->sum('priceAfterDiscount'),
                'qty' => collect($q)->sum('qty'),
            ],
        )->values()->toArray() as $key => $row)
                <tr>
                    <td>{{ ++$index }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td class="text-center">{{ number_format($row['qty']) }}</td>
                    <td class="text-end">{{ number_format($row['price']) }}</td>
                    <td class="text-end">{{ number_format($row['priceAfterDiscount']) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">By. Admin</td>
                <td></td>
                <td></td>
                <td class="text-end">{{ number_format($admin->sum('admin')) }}</td>
            </tr>
            <tr>
                <th colspan="3">Total</th>
                <th class="text-end">
                    {{ number_format(collect($data)->sum('price')) }}
                </th>
                <th class="text-end">
                    {{ number_format(collect($data)->sum('priceAfterDiscount') + $admin->sum('admin')) }}
                </th>
            </tr>
        </tbody>
    </table>
@else
    <table class="table table-bordered fs-10">
        <thead>
            <tr>
                <th class="w-10px">No.</th>
                <th>Tanggal</th>
                <th>Pasien</th>
                <th>Tindakan</th>
                <th class="w-70px">Qty</th>
                <th class="w-100px">Harga</th>
                <th class="w-100px">Diskon</th>
                <th class="w-100px">Harga Setelah Diskon</th>
                <th class="w-100px">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $index = 0;
                $total = 0;
            @endphp
            @foreach ($data as $key => $row)
                @php
                    $total += ($row['price'] - ($row['price'] * $row['discount']) / 100) * $row['qty'];
                @endphp
                <tr>
                    <td>{{ ++$index }}</td>
                    <td>{{ $row->payment->date }}</td>
                    <td>{{ $row->payment->registration->patient->name }}</td>
                    <td>{{ $row->actionRate->name }}</td>
                    <td class="text-center">{{ number_format($row['qty']) }}</td>
                    <td class="text-end">{{ number_format($row['price']) }}</td>
                    <td class="text-end">{{ number_format(($row['price'] * $row['discount']) / 100) }}</td>
                    <td class="text-end">{{ number_format($row['price'] - ($row['price'] * $row['discount']) / 100) }}
                    </td>
                    <td class="text-end">
                        {{ number_format(($row['price'] - ($row['price'] * $row['discount']) / 100) * $row['qty']) }}
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">By. Admin</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-end">{{ number_format($admin->sum('admin')) }}</td>
            </tr>
            <tr>
                <th colspan="5">Total Keseluruhan</th>
                <th class="text-end">
                    {{ number_format(collect($data)->sum('price')) }}
                </th>
                <th class="text-end">
                    {{ number_format(collect($data)->sum(fn($row) => ($row['price'] * $row['discount']) / 100)) }}
                </th>
                <th class="text-end">
                    {{ number_format(collect($data)->sum(fn($row) => $row['price'] - ($row['price'] * $row['discount']) / 100)) }}
                </th>
                <th class="text-end">
                    {{ number_format($total + $admin->sum('admin')) }}
                </th>
            </tr>
        </tbody>
    </table>
@endif
@if ($cetak)
    <div class="text-end">
        <small><small>Tgl. Cetak : {{ date('d-m-Y H:i:s') }}</small></small>
    </div>
@endif
