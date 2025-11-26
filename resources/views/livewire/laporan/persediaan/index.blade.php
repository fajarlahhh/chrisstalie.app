<div>
    @section('title', 'Persediaan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Laporan</li>
        <li class="breadcrumb-item active">Persediaan</li>
    @endsection

    <h1 class="page-header">Persediaan</h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <div class="w-100">
                <div class="panel-heading-btn float-end">
                    <select class="form-control w-auto" wire:model.lazy="persediaan">
                        <option value="">Semua Persediaan</option>
                        <option value="Apotek">Apotek</option>
                        <option value="Klinik">Klinik</option>
                    </select>&nbsp;
                    <select class="form-control w-auto" wire:model.lazy="kode_akun_id">
                        <option value="">Semua Kategori</option>
                        @foreach ($dataKodeAkun as $item)
                            <option value="{{ $item['id'] }}">{{ $item['id'] }} - {{ $item['nama'] }}</option>
                        @endforeach
                    </select>&nbsp;
                    <input type="text" class="form-control w-200px" placeholder="Cari"
                        aria-label="Sizing example input" autocomplete="off" aria-describedby="basic-addon2"
                        wire:model.lazy="cari">
                </div>
            </div>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="w-10px bg-gray-300 text-white">No.</th>
                        <th class="bg-gray-300 text-white">Nama</th>
                        <th class="bg-gray-300 text-white">Satuan</th>
                        <th class="bg-gray-300 text-white">Kategori</th>
                        <th class="bg-gray-300 text-white">Tanggal Kedaluarsa</th>
                        <th class="bg-gray-300 text-white">Harga Beli</th>
                        <th class="bg-gray-300 text-white">Stok</th>
                        <th class="bg-gray-300 text-white">Total Persediaan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        @php
                            $stok = $dataStok
                                ->where('barang_id', $item->id)
                                ->groupBy('tanggal_kedaluarsa', 'harga_beli')
                                ->map(function ($q) use ($item) {
                                    return [
                                        'tanggal_kedaluarsa' => $q->first()['tanggal_kedaluarsa'],
                                        'harga_beli' => $q->first()['harga_beli'],
                                        'stok' => $q->count() / $item->barangSatuanUtama?->rasio_dari_terkecil,
                                        'total' => $q->first()['harga_beli'] * $q->count(),
                                    ];
                                });
                        @endphp
                        <tr @if ($stok->count() > 0) class="bg-green-100" @endif>
                            <td @if ($stok->count() > 0) rowspan="{{ $stok->count() + 1 }}" @endif>
                                {{ $loop->iteration }}</td>
                            <td nowrap @if ($stok->count() > 0) rowspan="{{ $stok->count() + 1 }}" @endif>
                                {{ $item->nama }}</td>
                            <td nowrap @if ($stok->count() > 0) rowspan="{{ $stok->count() + 1 }}" @endif>
                                {{ $item->barangSatuanUtama?->nama }}
                                {{ $item->barangSatuanUtama?->konversi_satuan }}</td>
                            <td nowrap @if ($stok->count() > 0) rowspan="{{ $stok->count() + 1 }}" @endif>
                                {{ $item->kode_akun_id }} - {{ $item->kodeAkun?->nama }}</td>
                            @if ($stok->count() == 0)
                                <td nowrap></td>
                                <td nowrap class="text-end">0</td>
                                <td nowrap class="text-end">0</td>
                                <td nowrap class="text-end">0</td>
                            @endif
                        </tr>
                        @foreach ($stok as $subItem)
                            <tr class="bg-green-100">
                                <td nowrap class="text-end">{{ $subItem['tanggal_kedaluarsa'] }}</td>
                                <td nowrap class="text-end">{{ number_format($subItem['harga_beli']) }}</td>
                                <td nowrap class="text-end">{{ number_format($subItem['stok']) }}</td>
                                <td nowrap class="text-end">
                                    {{ number_format($subItem['total']) }}</td>
                            </tr>
                        @endforeach
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="7" class="text-end">Total Nilai Persediaan</th>
                        <td class="text-end">{{ number_format($dataStok->sum('harga_beli')) }}</td>
                </tfoot>
            </table>
        </div>
    </div>
    <x-alert />
</div>
