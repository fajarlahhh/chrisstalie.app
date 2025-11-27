@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <h4>Laporan Rekap Transaksi Barang</h4>
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
                <td>{{ ++$i }}</td>
                <td>{{ $row->nama }}</td>
                <td>{{ $row->barangSatuanUtama?->nama }} {{ $row->barangSatuanUtama?->konversi_satuan }}</td>
                <td class="text-end">
                    {{ number_format(
                        $row->stokAwal->map(
                                fn($q) => [
                                    'qty' => $row->rasio_dari_terkecil != 0 ? $q->qty / $row->rasio_dari_terkecil : 0,
                                ],
                            )->sum('qty'),
                        2,
                    ) }}
                </td>
                <td class="text-end">
                    {{ number_format(
                        $row->stokMasuk->map(
                                fn($q) => [
                                    'qty' => $row->rasio_dari_terkecil != 0 ? $q->qty / $row->rasio_dari_terkecil : 0,
                                ],
                            )->sum('qty'),
                        2,
                    ) }}
                </td>
                <td class="text-end">
                    {{ number_format(
                        $row->stokKeluar->map(
                                fn($q) => [
                                    'qty' => $row->rasio_dari_terkecil != 0 ? $q->qty / $row->rasio_dari_terkecil : 0,
                                ],
                            )->sum('qty'),
                        2,
                    ) }}
                </td>
                <td class="text-end">
                    {{ number_format(
                        $row->stokAwal->map(
                                fn($q) => [
                                    'qty' => $row->rasio_dari_terkecil != 0 ? $q->qty / $row->rasio_dari_terkecil : 0,
                                ],
                            )->sum('qty') +
                            $row->stokMasuk->map(
                                    fn($q) => [
                                        'qty' => $row->rasio_dari_terkecil != 0 ? $q->qty / $row->rasio_dari_terkecil : 0,
                                    ],
                                )->sum('qty') -
                            $row->stokKeluar->map(
                                    fn($q) => [
                                        'qty' => $row->rasio_dari_terkecil != 0 ? $q->qty / $row->rasio_dari_terkecil : 0,
                                    ],
                                )->sum('qty'),
                        2,
                    ) }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
