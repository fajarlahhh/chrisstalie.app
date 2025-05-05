<div>
    @section('title', 'Pegawai')

    @section('breadcrumb')
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item active">Pegawai</li>
    @endsection

    <h1 class="page-header">Pegawai</h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            @role('administrator|supervisor|operator')
                <a href="javascript:window.location.href=window.location.href.split('?')[0] + '/form'" class="btn btn-primary"
                    wire:ignore>
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
                        <th rowspan="2" class="w-10px">No.</th>
                        <th rowspan="2">NIK</th>
                        <th rowspan="2">Nama</th>
                        <th rowspan="2">Alamat</th>
                        <th rowspan="2">No. Hp</th>
                        <th rowspan="2">Tanggal Masuk</th>
                        <th colspan="4">Penggajian</th>
                        <th rowspan="2" class="w-10px"></th>
                    </tr>
                    <tr>
                        <th class="w-100px">Gaji</th>
                        <th class="w-100px">Tunjangan</th>
                        <th class="w-100px">Transport</th>
                        <th class="w-100px">BPJS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr>
                            <td>
                                {{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $row->nik }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->alamat }}</td>
                            <td>{{ $row->no_hp }}</td>
                            <td>{{ $row->tanggal_masuk }}</td>
                            <td class="text-end">{{ number_format($row->gaji) }}</td>
                            <td class="text-end">{{ number_format($row->tunjangan) }}</td>
                            <td class="text-end">{{ number_format($row->tunjangan_transport) }}</td>
                            <td class="text-end">{{ number_format($row->tunjangan_bpjs) }}</td>
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
