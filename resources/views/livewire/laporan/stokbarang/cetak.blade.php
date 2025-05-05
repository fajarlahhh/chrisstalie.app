@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <h4>Laporan Stok Barang</h4>
            <br>
            <small>Periode {{ $month }} {{ $year }}</small>
    </div>
    <br>
@endif
<table class="table table-bordered">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Barang</th>
            <th>Satuan</th>
            <th>Stok Awal</th>
            <th>Stok Masuk</th>
            <th>Stok Keluar</th>
            <th>Stok Akhir</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $row)
            <tr>
                @php
                    $stokMasuk = $row->stokMasuk->sum('qty');
                    $stokKeluar = $row->saleDetail->sum('qty');
                @endphp
                <td>{{ ++$i }}</td>
                <td>{{ $row['nama'] }}</td>
                <td>{{ $row['satuan'] }}</td>
                <td class="text-end">{{ number_format($row->goodsBalance->sum('qty'), 2) }}</td>
                <td class="text-end @if ($stokMasuk > 0) bg-green-100 @endif">
                    {{ number_format($stokMasuk, 2) }}
                </td>
                <td class="text-end @if ($stokKeluar > 0) bg-red-100 @endif">
                    {{ number_format($stokKeluar, 2) }}
                </td>
                <th class="text-end">
                    {{ number_format(
                        $row->goodsBalance->sum('qty') + $row->stokMasuk->sum('qty') - $row->saleDetail->sum('qty'),
                        2,
                    ) }}
                </th>
            </tr>
        @endforeach
    </tbody>
</table>
