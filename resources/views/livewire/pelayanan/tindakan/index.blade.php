<div>
    @section('title', 'Tindakan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pelayanan</li>
        <li class="breadcrumb-item active">Tindakan</li>
    @endsection

    <h1 class="page-header">Tindakan</h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <div class="w-100">
                <div class="panel-heading-btn float-end">
                    <select data-container="body" class="form-control" wire:model.lazy="status">
                        <option value="1">Belum Proses</option>
                        <option value="2">Sudah Proses</option>
                    </select>&nbsp;
                    @if ($status == 2)
                        <input class="form-control" type="date" wire:model.lazy="date" />&nbsp;
                    @endif
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
                        <th>Waktu Daftar</th>
                        <th>RM</th>
                        <th>Nama</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>Tindakan</th>
                        <th>Alat Bahan</th>
                        <th>Keterangan</th>
                        <th class="w-10px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr>
                            <td>
                                {{ ($data->currentpage() - 1) * $data->perpage() + $loop->index + 1 }}
                            </td>
                            <td>{{ $row->datetime }}</td>
                            <td>{{ $row->pasien->rm }}</td>
                            <td>{{ $row->pasien->nama }}</td>
                            <td>{{ $row->pasien->jenis_kelamin }}</td>
                            <td>{{ $row->pasien->alamat }}</td>
                            <td class="text-nowrap">
                                <ul>
                                    @foreach ($row->treatment as $subRow)
                                        <li>{{ $subRow->actionRate->nama }} ({{ $subRow->qty }} x
                                            {{ number_format($subRow->actionRate->harga, 2) }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="text-nowrap">
                                <ul>
                                    @foreach ($row->toolMaterial as $subRow)
                                        <li>{{ $subRow->goods->nama }} ({{ $subRow->qty }} x {{ $subRow->goods->harga }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $row->uraian }}</td>
                            <td class="with-btn-group text-end" nowrap>
                                @role('administrator|supervisor|operator')
                                    @if ($row->treatment->count() == 0)
                                        <a href="javascript:window.location.href=window.location.href.split('?')[0] + '/form/{{ $row['id'] }}'"
                                            class="btn btn-primary btn-sm">
                                            Input
                                        </a>
                                    @else
                                        @if ($row->payment)
                                            <x-action :row="$row" custom="" :detail="false" :edit="false"
                                                :print="false" :permanentDelete="false" :restore="false" :delete="false" />
                                        @else
                                            <x-action :row="$row" custom="" :detail="false" :edit="true"
                                                :print="false" :permanentDelete="false" :restore="false"
                                                :delete="true" />
                                        @endif
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
