<div>
    @section('title', 'Catatan Pasien')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pelayanan</li>
        <li class="breadcrumb-item active">Catatan Pasien</li>
    @endsection

    <h1 class="page-header">Catatan Pasien</h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <div class="w-100">
                <div class="panel-heading-btn float-end">
                    <select data-container="body" class="form-control" wire:model.lazy="status">
                        <option value="1">Belum Proses</option>
                        <option value="2">Sudah Proses</option>
                    </select>
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
                        <th>NIK</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>No. Telp.</th>
                        <th>Keterangan</th>
                        <th>Catatan Pasien</th>
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
                            <td>{{ $row->patient->rm }}</td>
                            <td>{{ $row->patient->name }}</td>
                            <td>{{ $row->patient->nik }}</td>
                            <td>{{ $row->patient->birth_date }}</td>
                            <td>{{ $row->patient->gender }}</td>
                            <td>{{ $row->patient->address }}</td>
                            <td>{{ $row->patient->phone }}</td>
                            <td>{{ $row->description }}</td>
                            <td>
                                {!! nl2br(e($row->note)) !!}
                            </td>
                            <td class="with-btn-group text-end" nowrap>
                                @role('administrator|supervisor|operator')
                                    @if (!$row->payment)
                                        @if (!$row->note)
                                            <a href="javascript:window.location.href=window.location.href.split('?')[0] + '/form/{{ $row['id'] }}'"
                                                class="btn btn-primary btn-sm">
                                                Input
                                            </a>
                                        @else
                                            <x-action :row="$row" custom="" :detail="false" :edit="true"
                                                :print="false" :permanentDelete="false" :restore="false" :delete="true" />
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
