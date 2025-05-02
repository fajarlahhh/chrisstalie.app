<div>
    @section('title', 'Pelunasan Pengadaan')

    @section('breadcrumb')
        <li class="breadcrumb-item active">Pelunasan Pengadaan</li>
    @endsection

    <h1 class="page-header">Pelunasan Pengadaan</h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            @role('administrator|supervisor|operator')
                <a href="javascript:window.location.href=window.location.href.split('?')[0] + '/form'"
                    class="btn btn-primary">Tambah</a>
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
                        <th>Pengadaan</th>
                        <th>Biaya</th>
                        <th>Detail</th>
                        <th class="w-10px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->description }}</td>
                            <td>{{ collect($row->purchase['purchase_detail'])->pluck('goods_name_qty')->join(',') }}
                                {{ $row->purchase['receipt'] }} - {{ $row->purchase['description'] }}</td>
                            <td>{{ number_format($row->cost) }}</td>
                            <td class="text-nowrap">
                                <ul>
                                    @foreach ($row->expenditureDetail as $subRow)
                                        <li>{{ $subRow->description }} : {{ number_format($subRow->cost) }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="with-btn-group text-end" nowrap>
                                @role('administrator|supervisor|operator')
                                    <x-action :row="$row" custom="" :detail="false" edit="form"
                                        :print="true" :permanentDelete="false" :restore="false" :delete="true" />
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
    <x-modal.cetak judul='Slip Pelunasan Pengadaan' />
</div>
