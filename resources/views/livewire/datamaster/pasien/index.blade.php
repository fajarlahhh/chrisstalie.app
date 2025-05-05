<div>
    @section('title', 'Pasien')

    @section('breadcrumb')
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item active">Pasien</li>
    @endsection

    <h1 class="page-header">Pasien</h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading">
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
                        <th>RM</th>
                        <th>IHS</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>No. Telp.</th>
                        <th>Tanggal Daftar</th>
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
                            <td>{{ $row->rm }}</td>
                            <td>{{ $row->ihs }}</td>
                            <td>{{ $row->nama }}</td>
                            <td>{{ $row->nik }}</td>
                            <td>{{ $row->tanggal_lahir }}</td>
                            <td>{{ $row->jenis_kelamin }}</td>
                            <td>{{ $row->alamat }}</td>
                            <td>{{ $row->no_hp }}</td>
                            <td>{{ $row->tanggal_daftar }}</td>
                            <td>{{ $row->uraian }}</td>
                            <td class="with-btn-group text-end" nowrap>
                @role('administrator|supervisor|operator')
                                    @if ($row->trashed())
                                        <x-action :row="$row"  custom="" :detail="false" :edit="false" :print="false"
                                            :permanentDelete="false" :restore="true" :delete="false" />
                                    @else
                                        <x-action :row="$row"  custom="" :detail="false" :edit="true" :print="false"
                                            :permanentDelete="true" :restore="false" :delete="true" />
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
