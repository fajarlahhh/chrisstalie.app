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
            @role('administrator|supervisor|operator')
                <a href="/pelayanan/pendaftaran" class="btn btn-primary">
                    Tambah</a>
            @endrole
            <div class="w-100">
                <div class="panel-heading-btn float-end">
                    <input class="form-control" type="date" wire:model.lazy="tanggal" />&nbsp;
                    <input type="text" class="form-control w-200px" placeholder="Cari"
                        aria-label="Sizing example input" autocomplete="off" aria-describedby="basic-addon2"
                        wire:model.lazy="cari">
                </div>
            </div>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w-10px">No.</th>
                        <th>Tanggal Daftar</th>
                        <th>RM</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Tanggal Lahir</th>
                        <th>Jenis Kelamin</th>
                        <th>Alamat</th>
                        <th>No. Telp.</th>
                        <th>Catatan</th>
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
                            <td>{{ $row->tanggal }}</td>
                            <td>{{ $row->pasien->rm }}</td>
                            <td>{{ $row->pasien->nama }}</td>
                            <td>{{ $row->pasien->nik }}</td>
                            <td>{{ $row->pasien->tanggal_lahir }}</td>
                            <td>{{ $row->pasien->jenis_kelamin }}</td>
                            <td>{{ $row->pasien->alamat }}</td>
                            <td>{{ $row->pasien->no_hp }}</td>
                            <td>{{ $row->catatan }}</td>
                            <td>
                                {{ $row->pelayananPemeriksaanAwal ? '1. Pemeriksaan Awal ' . $row->pelayananPemeriksaanAwal->pengguna->nama . ' (' . $row->pelayananPemeriksaanAwal->created_at . ')' : '' }}<br>
                                {{ $row->pelayananDiagnosa->count() > 0 ? '2. PelayananDiagnosa ' . $row->pelayananDiagnosa->first()->pengguna->nama . ' (' . $row->pelayananDiagnosa->first()->created_at . ')' : '' }}<br>
                                {{ $row->pelayananTindakan->count() > 0 ? '3. PelayananTindakan ' . $row->pelayananTindakan->first()->pengguna->nama . ' (' . $row->pelayananTindakan->first()->created_at . ')' : '' }}<br>
                                {{ $row->kasir ? '4. Kasir ' . $row->kasir->pengguna->nama . ' (' . $row->kasir->created_at . ')' : '' }}
                            </td>
                            <td class="with-btn-group text-end" nowrap>
                                @role('administrator|supervisor|operator')
                                    @if (!$row->kasir)
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
