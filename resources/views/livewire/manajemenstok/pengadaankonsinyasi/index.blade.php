<div>
    @section('title', 'Pengadaan Konsinyasi')

    @section('breadcrumb')
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item active">Pengadaan Konsinyasi</li>
    @endsection

    <h1 class="page-header">Pengadaan Konsinyasi</h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            @role('administrator|supervisor|operator')
                <a href="javascript:window.location.href=window.location.href.split('?')[0] + '/form'"
                    class="btn btn-primary">
                    Tambah</a>
            @endrole
            <div class="w-100">
                <div class="panel-heading-btn float-end">
                    <select wire:model.lazy="month" class="form-control w-auto">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ sprintf('%02s', $i) }}">
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                        @endfor
                    </select>&nbsp;
                    <select wire:model.lazy="year" class="form-control w-auto">
                        @for ($i = 2023; $i <= date('Y'); $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>&nbsp;
                    <input type="text" class="form-control w-200px" placeholder="Cari"
                        aria-label="Sizing example input" autocomplete="off" aria-describedby="basic-addon2"
                        wire:model.lazy="search">
                </div>
            </div>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w-10px">No.</th>
                        <th>Tanggal</th>
                        <th>Deskripsi</th>
                        <th>Barang/Item</th>
                        <th class="w-10px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr>
                            <td>{{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}</td>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->uraian }}</td>
                            <td class="w-400px">
                                <table class="table-bordered fs-10px">
                                    <tr class="bg-gray-100">
                                        <th class="text-nowrap w-250px p-1">Barang/Item</th>
                                        <th class="w-100px p-1">Harga Satuan</th>
                                        <th class="w-50px p-1">Qty</th>
                                        <th class="w-100px p-1">Harga</th>
                                    </tr>
                                    @foreach ($row->purchaseDetail as $j => $subRow)
                                        <tr>
                                            <td class="p-1">
                                                {{ $subRow->goods_id ? $subRow->goods->nama : $subRow->nama }}</td>
                                            <td class="text-end p-1  text-nowrap">
                                                {{ number_format($subRow->harga) }}</td>
                                            <td class="text-end p-1  text-nowrap">
                                                {{ number_format($subRow->qty) }}</td>
                                            <td class="text-end p-1  text-nowrap">
                                                {{ number_format($subRow->qty * $subRow->harga) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="p-1" colspan="3">Total</td>
                                        <td class="text-end p-1  text-nowrap">
                                            {{ number_format($row->purchaseDetail->sum(fn($q) => $q->harga * $q->qty)) }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td class="with-btn-group text-end" nowrap>
                @role('administrator|supervisor|operator')
                                    @if ($row->stokMasuk->count() == 0)
                                        <x-action :row="$row"  custom="" :detail="false" :edit="false" :print="false"
                                            :permanentDelete="false" :restore="false" :delete="true" />
                                    @endif
                                @endrole
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="panel-footer">
            {{ $data->links() }}
        </div>
    </div>
    <x-alert />
</div>
