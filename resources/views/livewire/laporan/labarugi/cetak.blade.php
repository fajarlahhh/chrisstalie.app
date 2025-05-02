@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <h4>Laporan Laba Rugi</h4>
        <br>
        <small>Periode {{ $month }}</small>
    </div>
    <br>
@endif
<table class="table table-bordered fs-10">
    <thead>
        <tr>
            <th colspan="3">PENDAPATAN</th>
        </tr>
        <tr>
            <th class="w-10px">No.</th>
            <th>Uraian</th>
            <th>Nilai</th>
        </tr>
        <tr>
            <td>1. </td>
            <td>Penerimaan Klinik</td>
            <td class="text-end">{{ number_format($penerimaan_klinik) }}</td>
        </tr>
        <tr>
            <td>2.</td>
            <td>Penerimaan Apotek</td>
            <td class="text-end">{{ number_format($penerimaan_apotek) }}</td>
        </tr>
        <tr>
            <th colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;TOTAL PENERIMAAN</th>
            <th class="text-end">{{ number_format($penerimaan_klinik + $penerimaan_apotek) }}</th>
        </tr>
        <tr>
            <th colspan="3">PENGELUARAN</th>
        </tr>
        <tr>
            <td>1. </td>
            <td colspan="2">Gaji Karyawan</td>
        </tr>
        @foreach ($gaji_pegawai as $item)
            <tr>
                <td></td>
                <td> - {{ $item['description'] }}</td>
                <td class="text-end">{{ number_format($item['cost']) }}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td>Total Gaji Karyawan</td>
            <td class="text-end">{{ number_format($gaji_pegawai->sum('cost')) }}</td>
        </tr>
        <tr>
            <td>2.</td>
            <td colspan="2">Pengeluaran Klinik</td>
        </tr>
        @foreach ($pengeluaran_klinik as $item)
            <tr>
                <td></td>
                <td> - {{ $item['expenditure_type'] }}</td>
                <td class="text-end">{{ number_format($item['cost']) }}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td>Total Pengeluaran Klinik</td>
            <td class="text-end">{{ number_format($pengeluaran_klinik->sum('cost')) }}</td>
        </tr>
        <tr>
            <td>3.</td>
            <td colspan="2">Pengeluaran Apotek</td>
        </tr>
        @foreach ($pengeluaran_apotek as $item)
            <tr>
                <td></td>
                <td> - {{ $item['expenditure_type'] }}</td>
                <td class="text-end">{{ number_format($item['cost']) }}</td>
            </tr>
        @endforeach
        <tr>
            <td></td>
            <td>Total Pengeluaran Klinik</td>
            <td class="text-end">{{ number_format($pengeluaran_apotek->sum('cost')) }}</td>
        </tr>
        <tr>
            <th colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;TOTAL PENGELUARAN</td>
            <th class="text-end">
                {{ number_format($gaji_pegawai->sum('cost') + $pengeluaran_klinik->sum('cost') + $pengeluaran_apotek->sum('cost')) }}
            </th>
        </tr>
        <tr>
            <th colspan="2">LABA RUGI</th>
            <th class="text-end">
                {{ number_format($penerimaan_klinik + $penerimaan_apotek - ($gaji_pegawai->sum('cost') + $pengeluaran_klinik->sum('cost') + $pengeluaran_apotek->sum('cost'))) }}
            </th>
        </tr>
    </thead>
</table>
@if ($cetak)
    <div class="text-end">
        <small><small>Tgl. Cetak : {{ date('d-m-Y H:i:s') }}</small></small>
    </div>
@endif
