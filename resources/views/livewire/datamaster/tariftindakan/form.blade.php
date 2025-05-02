<div>
    @section('title', (!$data->exists ? 'Tambah' : 'Edit') . ' Tindakan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item">Tindakan</li>
        <li class="breadcrumb-item active">{{ !$data->exists ? 'Tambah' : 'Edit' }}</li>
    @endsection

    <h1 class="page-header">Tindakan <small>{{ !$data->exists ? 'Tambah' : 'Edit' }}</small></h1>

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
                    <input class="form-control" type="text" wire:model="name" />
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select data-container="body" class="form-control " wire:model="category" data-width="100%">
                        <option selected hidden>-- Pilih Kategori --</option>
                        <option value="Medis">Medis</option>
                        <option value="Non Medis">Non Medis</option>
                    </select>
                    @error('category')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Biaya</label>
                    <input class="form-control" type="number" wire:model.lazy="price" />
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Modal</label>
                    <input class="form-control" type="number" wire:model.lazy="capital" />
                    @error('capital')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <hr>
                <div class="mb-3">
                    <label class="form-label">Keuntungan</label>
                    <input class="form-control" type="number" value="{{ $price - $capital }}" disabled />
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Upah Beautician</label>
                    <input class="form-control" type="number" wire:model.lazy="beautician_fee" />
                    @error('beautician_fee')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Porsi Klinik <small class="text-warning">(% dari
                            Keuntungan)</small></label>
                    <div class="input-group mb-3">
                        <input class="form-control" type="number" max="100" min="0"
                            wire:model.lazy="office_portion_percent" />
                        <div class="input-group-text w-350px">
                            {{ (($price - $capital - $beautician_fee) * $office_portion_percent) / 100 }}</div>
                    </div>
                    @error('office_portion_percent')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Porsi Nakes <small class="text-warning">(Rp.)</small></label>
                    <input class="form-control" type="number"
                        value="{{ (($price - $capital - $beautician_fee) * (100 - $office_portion_percent)) / 100 }}"
                        disabled />
                    @error('practitioner_portion')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode ICD 9 CM</label>
                    <input class="form-control" type="text" wire:model="icd_9_cm_code" />
                    @error('icd_9_cm_code')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input class="form-control" type="text" wire:model="description" />
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
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
