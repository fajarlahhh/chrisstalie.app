<div>
    @section('title', 'Data Pendaftaran')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pelayanan</li>
        <li class="breadcrumb-item">Pendaftaran</li>
        <li class="breadcrumb-item active">Data</li>
    @endsection

    <h1 class="page-header">Pendaftaran <small>Data</small></h1>

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading">
            <div class="w-100">
                <div class="panel-heading-btn float-end">
                    <input class="form-control" type="date" wire:model.lazy="date" />&nbsp;
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
                        <th>NIK</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>No. Telp.</th>
                        <th>Keterangan</th>
                        <th>Proses</th>
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
                                {{ $row->initialExamination ? '1. Pemeriksaan Awal ' . $row->initialExamination->user->name . ' (' . $row->initialExamination->created_at . ')' : '' }}<br>
                                {{ $row->diagnosis->count() > 0 ? '2. Diagnosis ' . $row->diagnosis->first()->user->name . ' (' . $row->diagnosis->first()->created_at . ')' : '' }}<br>
                                {{ $row->treatment->count() > 0 ? '3. Tindakan ' . $row->treatment->first()->user->name . ' (' . $row->treatment->first()->created_at . ')' : '' }}<br>
                                {{ $row->payment ? '4. Kasir ' . $row->payment->user->name . ' (' . $row->payment->created_at . ')' : '' }}
                            </td>
                            <td class="with-btn-group text-end" nowrap>
                                @role('administrator|supervisor|operator')
                                    @if (!$row->payment)
                                        <x-action :row="$row" custom="" :detail="false" :edit="false"
                                            :print="false" :permanentDelete="false" :restore="false" :delete="true" />
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
