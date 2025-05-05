<div>
    @section('title', (!$data->exists ? 'Tambah' : 'Edit') . ' PelayananTindakan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item">PelayananTindakan</li>
        <li class="breadcrumb-item active">{{ !$data->exists ? 'Tambah' : 'Edit' }}</li>
    @endsection

    <h1 class="page-header">PelayananTindakan <small>{{ !$data->exists ? 'Tambah' : 'Edit' }}</small></h1>

    <x-alert />

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">

            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit">
            <div class="panel-body">
                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input class="form-control" type="text" wire:model="nama" />
                    @error('nama')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select data-container="body" class="form-control " wire:model="kategori" data-width="100%">
                        <option selected hidden>-- Pilih Kategori --</option>
                        <option value="Medis">Medis</option>
                        <option value="Non Medis">Non Medis</option>
                    </select>
                    @error('kategori')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input class="form-control" type="text" wire:model="deskripsi" />
                    @error('deskripsi')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode ICD 9 CM</label>
                    <input class="form-control" type="text" wire:model="icd_9_cm" />
                    @error('icd_9_cm')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="alert alert-secondary">
                    <h4>Biaya</h4>
                    <div class="mb-3">
                        <label class="form-label">Modal</label>
                        <input class="form-control" type="number" wire:model.live="modal" />
                        @error('modal')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Porsi Beautician</label>

                        <input class="form-control" type="number" wire:model.live="porsi_petugas" />
                        @error('porsi_petugas')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Porsi Nakes</label>
                        <input class="form-control" type="number" wire:model.live="porsi_nakes" />
                        @error('porsi_nakes')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Porsi Klinik</label>
                        <input class="form-control" type="number" wire:model.live="porsi_kantor" />
                        @error('porsi_kantor')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Biaya</label>
                        <input class="form-control" type="number" disabled
                            value="{{ $modal + $porsi_petugas + $porsi_kantor + $porsi_nakes }}" />
                        @error('harga')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
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
