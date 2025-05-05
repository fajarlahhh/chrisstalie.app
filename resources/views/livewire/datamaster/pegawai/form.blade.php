<div>
    @section('title', (!$data->exists ? 'Tambah' : 'Edit') . ' Pegawai')

    @section('breadcrumb')
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item">Pegawai</li>
        <li class="breadcrumb-item active">{{ !$data->exists ? 'Tambah' : 'Edit' }}</li>
    @endsection

    <h1 class="page-header">Pegawai <small>{{ !$data->exists ? 'Tambah' : 'Edit' }}</small></h1>

    <x-alert />

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">

            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">NIK</label>
                            <input class="form-control" type="number" step="1" maxlength="16" minlength="16"
                                wire:model="nik" />
                            @error('nik')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input class="form-control" type="text" wire:model="nama" />
                            @error('nama')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input class="form-control" type="text" wire:model="alamat" />
                            @error('alamat')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. Hp</label>
                            <input class="form-control" type="text" wire:model="no_hp" />
                            @error('no_hp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select data-container="body" class="form-control " wire:model="jenis_kelamin" data-width="100%">
                                <option selected>-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input class="form-control" type="date" wire:model="tanggal_lahir" />
                            @error('tanggal_lahir')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Masuk</label>
                            <input class="form-control" type="date" wire:model="tanggal_masuk" />
                            @error('tanggal_masuk')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NPWP</label>
                            <input class="form-control" type="text" wire:model="npwp" />
                            @error('npwp')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No. BPJS Kesehatan</label>
                            <input class="form-control" type="text" wire:model="no_bpjs" />
                            @error('no_bpjs')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jabatan</label>
                            <input class="form-control" type="text" wire:model="posisi" />
                            @error('posisi')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="note alert-secondary mb-0">
                            <div class="note-content">
                                <h4>Gaji & Tunjangan</h4>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label">Gaji Pokok</label>
                                    <input class="form-control" type="number" step="1" min="0"
                                        wire:model="gaji" />
                                    @error('gaji')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tunjangan</label>
                                    <input class="form-control" type="number" step="1" min="0"
                                        wire:model="tunjangan" />
                                    @error('tunjangan')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tunjangan Transport</label>
                                    <input class="form-control" type="number" step="1" min="0"
                                        wire:model="tunjangan_transport" />
                                    @error('tunjangan_transport')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">BPJS Kesehatan</label>
                                    <input class="form-control" type="number" step="1" min="0"
                                        wire:model="tunjangan_bpjs" />
                                    @error('tunjangan_bpjs')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                @role('administrator|supervisor|operator')
                    <input type="submit" value="Simpan" class="btn btn-success" />
                @endrole
                <a href="{{ $previous }}" class="btn btn-danger" wire:ignore>Batal</a>
            </div>
        </form>
    </div>
</div>
