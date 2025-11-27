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
            <td>{{ $bulan }}</td>
        </tr>
        <tr>
            <th>Jenis</th>
            <th class="w-10px">:</th>
            <td>{{ $jenis }}</td>
        </tr>
    </table>
@endif
@if ($jenis == 'Rekap')
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Satuan</th>
                <th>Harga Jual</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $row)
                <tr>
                    <td @if (count($row) > 1) rowspan="{{ count($row) + 1 }}" @endif>{{ $loop->iteration }}
                    </td>
                    <td @if (count($row) > 1) rowspan="{{ count($row) + 1 }}" @endif nowrap>
                        {{ $key }}</td>
                    @if (count($row) == 1)
                        <td nowrap>{{ $row[0]['barang'] }}</td>
                        <td nowrap>{{ $row[0]['satuan'] }}</td>
                        <td nowrap class="text-end">{{ number_format($row[0]['harga_jual']) }}</td>
                        <td nowrap class="text-end">{{ number_format($row[0]['qty']) }}</td>
                        <td nowrap class="text-end">{{ number_format($row[0]['total']) }}</td>
                    @endif
                </tr>
                @if (count($row) > 1)
                    @foreach ($row as $item)
                        <tr>
                            <td nowrap>{{ $item['barang'] }}</td>
                            <td nowrap>{{ $item['satuan'] }}</td>
                            <td nowrap class="text-end">{{ number_format($item['harga_jual']) }}</td>
                            <td nowrap class="text-end">{{ number_format($item['qty']) }}</td>
                            <td nowrap class="text-end">{{ number_format($item['total']) }}</td>
                        </tr>
                    @endforeach
                @endif
            @endforeach
        </tbody>
    </table>
@else
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Satuan</th>
                <th>Tanggal Kedaluarsa</th>
                <th>Harga Jual</th>
                <th>Qty</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($data as $key => $row)
                @php
                    $grouped = collect($row)->groupBy('tanggal_kedaluarsa')->sortBy('tanggal_kedaluarsa');
                @endphp
                <tr>
                    <td @if (count($grouped) > 1) rowspan="{{ count($grouped) + 1 }}" @endif>
                        {{ $loop->iteration }}
                    </td>
                    <td @if (count($grouped) > 1) rowspan="{{ count($grouped) + 1 }}" @endif nowrap>
                        {{ $key }}</td>
                    @if (count($grouped) == 1)
                        <td nowrap>{{ $row[0]['barang'] }}</td>
                        <td nowrap>{{ $row[0]['satuan'] }}</td>
                        <td nowrap>{{ $row[0]['tanggal_kedaluarsa'] }}</td>
                        <td nowrap class="text-end">{{ number_format($row[0]['harga_jual']) }}</td>
                        <td nowrap class="text-end">{{ number_format($row[0]['qty']) }}</td>
                        <td nowrap class="text-end">
                            {{ number_format(collect($row)->sum(fn($q) => $q['qty'] * $q['harga_jual'])) }}</td>
                        @php
                            $total += collect($row)->sum(fn($q) => $q['qty'] * $q['harga_jual']);
                        @endphp
                    @endif
                </tr>
                @if (count($grouped) > 1)
                    @foreach ($grouped as $key => $item)
                        <tr>
                            <td nowrap>{{ $item->first()['barang'] }}</td>
                            <td nowrap>{{ $item->first()['satuan'] }}</td>
                            <td nowrap>{{ $key }}</td>
                            <td nowrap class="text-end">{{ number_format($item->first()['harga_jual']) }}</td>
                            <td nowrap class="text-end">{{ number_format($item->sum('qty')) }}</td>
                            <td nowrap class="text-end">
                                {{ number_format($item->sum(fn($q) => $q['qty'] * $q['harga_jual'])) }}</td>
                            @php
                                $total += $item->sum(fn($q) => $q['qty'] * $q['harga_jual']);
                            @endphp
                        </tr>
                    @endforeach
                @endif
            @endforeach
            <tr>
                <td colspan="7" class="text-end">Total</td>
                <td class="text-end">{{ number_format($total) }}
                </td>
            </tr>
        </tbody>
    </table>
@endif
