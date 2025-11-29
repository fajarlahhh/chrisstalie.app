@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Laporan Harian Kas</h5>
        <hr>
    </div>
    <br>
    <table>
        <tr>
            <th class="w-100px">Periode</th>
            <th class="w-10px">:</th>
            <td>{{ $tanggal }}</td>
        </tr>
        <tr>
            <th class="w-100px">Pengguna</th>
            <th class="w-10px">:</th>
            <td>{{ $pengguna }}</td>
        </tr>
    </table>
@endif
@php
    $pendapatan = [];
    foreach (collect($dataMetodeBayar) as $key => $item) {
        $totalPendapatan[$key] = [];
    }
    foreach (collect($dataKodeAkun)->where('parent_id', '11100') as $item) {
        $totalPengeluaran[$item['id']] = [];
    }
@endphp
<table class="table table-bordered">
    <tr>
        <th class="bg-gray-300 text-white" rowspan="2">No.</th>
        <th class="bg-gray-300 text-white" rowspan="2" colspan="2">Keterangan</th>
        <th class="bg-gray-300 text-white" colspan="{{ count($dataMetodeBayar) }}">Jumlah</th>
    </tr>
    <tr>
        @foreach (collect($dataMetodeBayar) as $item)
            <th class="w-100px bg-gray-300 text-white">{{ $item['nama'] }}</th>
        @endforeach
    </tr>
    <tr>
        <th class="w-10px">1.</th>
        <th colspan="{{ count($dataMetodeBayar) + 2 }}">Pendapatan</th>
    </tr>
    <tr>
        <td></td>
        <td class="w-10px">a.</td>
        <td colspan="{{ count($dataMetodeBayar) + 1 }}">Pendapatan Klinik</td>
    </tr>
    @foreach (collect($dataKodeAkun)->where('parent_id', '42000') as $item)
        <tr>
            <td></td>
            <td class="w-10px"></td>
            <td>-&nbsp;&nbsp;{{ $item['nama'] }}</td>
            @foreach (collect($dataMetodeBayar) as $key => $metodeBayar)
                @php
                    $pendapatan = $data
                        ->where('metode_bayar', $metodeBayar['nama'])
                        ->where('kode_akun_id', $item['id'])
                        ->sum('kredit');
                    $totalPendapatan[$key][$item['id']] = $pendapatan;
                @endphp
                <td class="text-end">
                    {{ number_format($pendapatan) }}
                </td>
            @endforeach
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td class="w-10px">b.</td>
        <td colspan="{{ count($dataMetodeBayar) + 1 }}">Pendapatan Apotek</td>
    </tr>
    @foreach (collect($dataKodeAkun)->where('parent_id', '41000') as $item)
        <tr>
            <td></td>
            <td class="w-10px"></td>
            <td>-&nbsp;&nbsp;{{ $item['nama'] }}</td>
            @foreach (collect($dataMetodeBayar) as $key => $metodeBayar)
                @php
                    $pendapatan = $data
                        ->where('metode_bayar', $metodeBayar['nama'])
                        ->where('kode_akun_id', $item['id'])
                        ->sum('kredit');
                    $totalPendapatan[$key][$item['id']] = $pendapatan;
                @endphp
                <td class="text-end">
                    {{ number_format($pendapatan) }}
                </td>
            @endforeach
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td class="w-10px">c.</td>
        <td>Pendapatan Pendapatan Non Usaha</td>
        <td class="text-end">0</td>
        <td class="text-end">0</td>
        <td class="text-end">0</td>
        <td class="text-end">0</td>
    </tr>
    <tr>
        <td></td>
        <td class="w-10px"></td>
        <td>Total Pendapatan</td>
        @foreach (collect($dataMetodeBayar) as $key => $metodeBayar)
            <td class="text-end">{{ number_format(collect($totalPendapatan[$key])->values()->sum()) }}
            </td>
        @endforeach
    </tr>
    <tr>
        <th class="w-10px">2.</th>
        <th colspan="{{ count($dataMetodeBayar) + 3 }}">Pengeluaran</th>
    </tr>
    @foreach (collect($dataKodeAkun)->filter(function ($item) use ($data) {
        return in_array($item['id'], $data->pluck('kode_akun_id')->unique()->toArray()) && str_starts_with($item['id'], '6');
    }) as $item)
        <tr>
            <td></td>
            <td class="w-10px"></td>
            <td>-&nbsp;&nbsp;{{ $item['nama'] }}</td>
            @foreach (collect($dataKodeAkun)->where('parent_id', '11100') as $subItem)
                <td class="text-end" nowrap>
                    @php
                        $debet = $data
                            ->whereIn('id', $data->where('kode_akun_id', $item['id'])->pluck('id')->unique()->toArray())
                            ->sum('debet');
                    @endphp
                    @if ($data->whereIn('id', $data->where('kode_akun_id', $item['id'])->pluck('id')->unique()->toArray())->where('kode_akun_id', $subItem['id'])->count() > 0)
                        {{ number_format($debet) }}
                        @php
                            $totalPengeluaran[$subItem['id']][$item['id']] = $debet;
                        @endphp
                    @else
                        {{ number_format(0) }}
                    @endif
                </td>
            @endforeach
            <td class="text-end">
                0
            </td>
            <td class="text-end">
                0
            </td>
        </tr>
    @endforeach
    <tr>
        <td></td>
        <td class="w-10px"></td>
        <td>Total Pengeluaran</td>
        @foreach (collect($dataKodeAkun)->where('parent_id', '11100') as $subItem)
            <td class="text-end">{{ number_format(collect($totalPengeluaran[$subItem['id']])->values()->sum()) }}
            </td>
        @endforeach
        <td class="text-end">0</td>
        <td class="text-end">0</td>
    </tr>
    <tr>
        <th class="w-10px">3.</th>
        <th colspan="2">Total</th>
        @foreach (collect($dataKodeAkun)->where('parent_id', '11100')->values() as $key => $subItem)
            <th class="text-end">
                {{ number_format(collect($totalPendapatan[$key])->values()->sum() -collect($totalPengeluaran[$subItem['id']])->values()->sum()) }}
            </th>
        @endforeach
        @foreach (array_slice($dataMetodeBayar, 2) as $key => $subItem)
            <th class="text-end">
                {{ number_format(collect($totalPendapatan[$key + 2])->values()->sum()) }}
            </th>
        @endforeach
    </tr>
</table>
