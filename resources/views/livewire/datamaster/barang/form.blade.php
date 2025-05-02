<div>
    @section('title', (!$data->exists ? 'Tambah' : 'Edit') . ' Data Barang')

    @section('breadcrumb')
        <li class="breadcrumb-item">Data Master</li>
        <li class="breadcrumb-item">Barang</li>
        <li class="breadcrumb-item active">{{ !$data->exists ? 'Tambah' : 'Edit' }}</li>
    @endsection

    <h1 class="page-header">Barang <small>{{ !$data->exists ? 'Tambah' : 'Edit' }}</small></h1>

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
                    <label class="form-label">Satuan</label>
                    <input class="form-control" type="text" wire:model="unit" />
                    @error('unit')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Stok Min.</label>
                    <input class="form-control" type="number" wire:model="min_inventory" />
                    @error('min_inventory')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Harga Jual</label>
                    <input class="form-control" type="number" wire:model="price" />
                    @error('price')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Barang</label>
                    <select class="form-control" wire:model="type" data-width="100%">
                        <option hidden selected>-- Pilih Jenis Barang --</option>
                        @foreach (\App\Enums\GoodstypeEnum::cases() as $item)
                            <option value="{{ $item->value }}">{{ $item->label() }}</option>
                        @endforeach
                    </select>
                    @error('type')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="alert alert-info">
                    <div class="mb-3">
                        <label class="form-label">Konsinyasi</label>
                        <select x-init="$($el).selectpicker({
                            liveSearch: true,
                            width: 'auto',
                            size: 10,
                            container: 'body',
                            style: '',
                            showSubtext: true,
                            styleBase: 'form-control'
                        })" class="form-control" wire:model.live="consignment_id"
                            data-width="100%">
                            <option selected value="">-- Pilih Konsinyasi --</option>
                            @foreach ($supplierData as $item)
                                <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                            @endforeach
                        </select>
                        @error('consignment_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    @role('administrator|supervisor|operator')
                        @if ($consignment_id)
                            <div class="mb-3">
                                <label class="form-label">Modal</label>
                                <input class="form-control" type="number" wire:model="capital" />
                                @error('capital')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bagian Apotek</label>
                                <input class="form-control" type="number" wire:model="office_portion" step="1" max="100" />
                                @error('office_portion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bagian Dokter</label>
                                <input class="form-control" step="1" type="number" wire:model="practitioner_portion"
                                    max="100" />
                                @error('practitioner_portion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    @endrole
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input class="form-control" type="text" wire:model="description" />
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">KFA</label>
                    <input class="form-control" type="text" wire:model="kfa" />
                    @error('kfa')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="precompounded" value="1"
                        wire:model="precompounded" />
                    <label class="form-check-label" for="precompounded">
                        Obat racikan siap pakai
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
