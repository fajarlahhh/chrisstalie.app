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
                        <th class="w-10px">No.</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No. Telpon</th>
                        <th>Jenis Kelamin</th>
                        <th>Tanggal Mulai</th>
                        <th>Jabatan</th>
                        <th>NPWP</th>
                        <th>No. BPJS Ketenagakerjaan</th>
                        <th>No. BPJS Kesehatan</th>
                        <th>Penggajian</th>
                        <th class="w-10px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr>
                            <td>
                                {{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $row->nik }}</td>
                            <td>{{ $row->name }}</td>
                            <td>{{ $row->address }}</td>
                            <td>{{ $row->phone_number }}</td>
                            <td>{{ $row->gender }}</td>
                            <td>{{ $row->start_date }}</td>
                            <td>{{ $row->position }} - {{ $row->office }}</td>
                            <td>{{ $row->npwp }}</td>
                            <td>{{ $row->bpjs_employment }}</td>
                            <td>{{ $row->bpjs_health }}</td>
                            <td class="text-nowrap">
                                Gaji Pokok : {{ number_format($row->wages) }}<br>
                                Tunjangan : {{ number_format($row->allowance) }}<br>
                                Transport : {{ number_format($row->transport_allowance) }}<br>
                                BPJS Kesehatan : {{ number_format($row->bpjs_health_cost) }}
                            </td>
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
