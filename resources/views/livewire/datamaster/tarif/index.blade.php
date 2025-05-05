<div>
    @section('title', 'PelayananTindakan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item active">PelayananTindakan</li>
    @endsection

    <h1 class="page-header">PelayananTindakan</h1>
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
                    <select data-container="body" class="form-control "wire:model.lazy="exist">
                        <option value="1">Exist</option>
                        <option value="2">Deleted</option>
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
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Kode ICD 9 CM</th>
                        <th>Modal</th>
                        <th>Porsi Beautician</th>
                        <th>Porsi Nakes</th>
                        <th>Porsi Klinik</th>
                        <th>Total Biaya</th>
                        <th class="w-10px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr>
                            <td>
                                {{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->kategori }}</td>
                            <td>{{ $row->deskripsi }}</td>
                            <td>{{ $row->icd_9_cm }}</td>
                            <td class="text-end">{{ number_format($row->modal) }}</td>
                            <td class="text-end">{{ number_format($row->porsi_petugas) }}</td>
                            <td class="text-end">{{ number_format($row->porsi_kantor) }}</td>
                            <td class="text-end">{{ number_format($row->porsi_nakes) }}</td>
                            <th class="text-end">{{ number_format($row->biaya) }}</th>
                            <td class="with-btn-group text-end" nowrap>
                                @role('administrator|supervisor|operator')
                                    @if ($row->trashed())
                                        <x-action :row="$row" custom="" :detail="false" :edit="false"
                                            :print="false" :permanentDelete="false" :restore="true" :delete="false" />
                                    @else
                                        <x-action :row="$row" custom="" :detail="false" :edit="true"
                                            :print="false" :permanentDelete="true" :restore="false" :delete="true" />
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
