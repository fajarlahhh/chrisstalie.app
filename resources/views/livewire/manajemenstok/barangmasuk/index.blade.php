<div>
    @section('title', 'Barang Masuk')

    @section('breadcrumb')
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item active">Barang Masuk</li>
    @endsection

    <h1 class="page-header">Barang Masuk</h1>
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
                        <th>Barang</th>
                        <th>Qty</th>
                        <th>Harga Beli</th>
                        <th>Tanggal Kadaluarsa</th>
                        <th>Supplier</th>
                        <th>Konsinyasi</th>
                        <th>Deskripsi</th>
                        <th class="w-10px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr>
                            <td>
                                {{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->goods?->nama }}</td>
                            <td>{{ $row->qty }}</td>
                            <td>{{ $row->purchase_harga }}</td>
                            <td>{{ $row->expired_date }}</td>
                            <td>{{ $row->purchase?->supplier?->nama }}</td>
                            <td>{{ $row->purchase?->supplier?->konsinyasi == 1 ? 'Ya' : '' }}</td>
                            <td>{{ $row->uraian }}</td>
                            <td class="with-btn-group text-end" nowrap>
                                @role('administrator|supervisor|operator')
                                    <x-action :row="$row" custom="" :detail="false" :edit="false"
                                        :print="false" :permanentDelete="false" :restore="false" :delete="true" />
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
