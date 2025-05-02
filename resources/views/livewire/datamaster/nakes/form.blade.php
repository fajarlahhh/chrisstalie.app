<div>
    @section('title', (!$data->exists ? 'Tambah' : 'Edit') . ' Nakes')

    @section('breadcrumb')
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item">Nakes</li>
        <li class="breadcrumb-item active">{{ !$data->exists ? 'Tambah' : 'Edit' }}</li>
    @endsection

    <h1 class="page-header">Nakes <small>{{ !$data->exists ? 'Tambah' : 'Edit' }}</small></h1>

    <x-alert />

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            
            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit">
            <div class="panel-body">
                <div class="mb-3">
                    <label class="form-label">Pegawai</label>
                    <select data-container="body" class="form-control" x-init="$($el).selectpicker({
                        liveSearch: true,
                        width: 'auto',
                        size: 10,
                        container: 'body',
                        style: '',
                        showSubtext: true,
                        styleBase: 'form-control'
                    })"
                        wire:model.lazy="employee_id" data-width="100%">
                        <option selected value="">-- Bukan Pegawai --</option>
                        @foreach ($employeeData as $employee)
                            <option value="{{ $employee['id'] }}" data-subtext="{{ $employee['nik'] }}">
                                {{ $employee['name'] }}</option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (!$employee_id)
                    <div class="mb-3">
                        <label class="form-label">NIK</label>
                        <input class="form-control" type="number" step="1" maxlength="16" minlength="16"
                            wire:model="nik" />
                        @error('nik')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">IHS</label>
                    <input class="form-control" type="text" wire:model="ihs" disabled />
                    @error('ihs')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (!$employee_id)
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input class="form-control" type="text" wire:model="name" />
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <input class="form-control" type="text" wire:model="address" />
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No. Telp.</label>
                        <input class="form-control" type="text" wire:model="phone_number" />
                        @error('phone_number')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select data-container="body" class="form-control " wire:model="gender" data-width="100%">
                            <option selected>-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        @error('gender')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input class="form-control" type="text" wire:model="description" />
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="doctor" value="1" wire:model="doctor" />
                    <label class="form-check-label" for="doctor">
                        Dokter
                    </label>
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
