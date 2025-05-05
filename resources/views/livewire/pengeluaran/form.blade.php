<div>
    @section('title', 'Pengeluaran')

    @section('breadcrumb')
        <li class="breadcrumb-item ">Pengeluaran</li>
        <li class="breadcrumb-item active">{{ !$data->exists ? 'Tambah' : 'Edit' }}</li>
    @endsection

    <h1 class="page-header">Pengeluaran <small>{{ !$data->exists ? 'Tambah' : 'Edit' }}</small>
    </h1>

    <x-alert />

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit">
            <div class="panel-body">
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input class="form-control" type="date" wire:model="date" />
                    @error('date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                {{-- <div class="mb-3">
                    <label class="form-label">Pengeluaran</label>
                    <select x-init="$($el).selectpicker({
                        liveSearch: true,
                        width: 'auto',
                        size: 10,
                        container: 'body',
                        style: '',
                        showSubtext: true,
                        styleBase: 'form-control'
                    })" class="form-control" wire:model.live="monthly_expenses_id"
                        data-width="100%">
                        <option selected value="">-- Pilih Pengeluaran --</option>
                        @foreach ($expenditureData as $item)
                            <option value="{{ $item['nama'] }}">{{ $item['nama'] }}</option>
                        @endforeach
                    </select>
                    @error('monthly_expenses_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div> --}}
                <div class="mb-3">
                    <label class="form-label">Kantor</label>
                    <select class="form-control" wire:model="office" data-width="100%">
                        <option selected value="">-- Pilih Kantor --</option>
                        <option value="Klinik">Klinik</option>
                        <option value="Apotek">Apotek</option>
                    </select>
                    @error('office')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Pengeluaran</label>
                    <select class="form-control" wire:model="expenditure_type" data-width="100%">
                        <option selected value="">-- Pilih Kantor --</option>
                        <option value="BIAYA UMUM">BIAYA UMUM</option>
                        <option value="BIAYA PEMELIHARAAN">BIAYA PEMELIHARAAN</option>
                        <option value="PAJAK">PAJAK</option>
                        <option value="OPERASIONAL">OPERASIONAL</option>
                        <option value="PDAM">PDAM</option>
                        <option value="TELKOM">TELKOM</option>
                        <option value="PLN">PLN</option>
                    </select>
                    @error('expenditure_type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                @if (!$monthly_expenses_id)
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <input class="form-control" type="text" wire:model="uraian" />
                        @error('uraian')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                <div class="mb-3">
                    <label class="form-label">Nilai</label>
                    <input class="form-control" type="number" wire:model="cost" />
                    @error('cost')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">No. Nota</label>
                    <input class="form-control" type="text" wire:model="receipt" />
                    @error('receipt')
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
