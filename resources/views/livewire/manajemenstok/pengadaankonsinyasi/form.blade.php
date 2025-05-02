<div>
    @section('title', 'Tambah Pengadaan Konsinyasi')

    @section('breadcrumb')
        <li class="breadcrumb-item">Apotek</li>
        <li class="breadcrumb-item">Pengadaan Konsinyasi</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">Pengadaan Konsinyasi <small>Tambah</small></h1>

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
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input class="form-control" type="text" wire:model="description" />
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Supplier</label>
                    <select data-container="body" class="form-control" x-init="$($el).selectpicker({
                        liveSearch: true,
                        width: 'auto',
                        size: 10,
                        container: 'body',
                        style: '',
                        showSubtext: true,
                        styleBase: 'form-control'
                    })" wire:model.live="supplier_id"
                        data-width="100%">
                        <option selected value="">-- Pilih Supplier --</option>
                        @foreach ($supplierData as $row)
                            <option value="{{ $row['id'] }}"
                                data-subtext="{{ $row['consignment'] == 1 ? 'Konsinyasi' : '' }}">
                                {{ $row['name'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="note alert-secondary mb-0">
                    <div class="note-content table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Barang/Item</th>
                                    <th class="w-100px">Qty</th>
                                    <th class="w-5px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($procurement as $index => $row)
                                    <tr>
                                        <td class="with-btn">
                                            <select class="form-control" x-init="$($el).selectpicker({
                                                liveSearch: true,
                                                width: 'auto',
                                                size: 10,
                                                container: 'body',
                                                style: '',
                                                showSubtext: true,
                                                styleBase: 'form-control'
                                            })"
                                                wire:model="procurement.{{ $index }}.goods_id">
                                                <option value="">-- Pilih Barang/Item --</option>
                                                @foreach ($goodsData as $subRow)
                                                    <option value="{{ $subRow['id'] }}"
                                                        data-subtext="{{ $subRow['unit'] }}">
                                                        {{ $subRow['name'] . ' (' . $subRow['type'] . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('procurement.' . $index . '.goods_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="with-btn">
                                            <input type="number" class="form-control w-100px" min="0"
                                                step="1" wire:model="procurement.{{ $index }}.qty"
                                                autocomplete="off">
                                            @error('procurement.' . $index . '.qty')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="with-btn">
                                            <a href="javascript:;" class="btn btn-danger"
                                                wire:click="deleteProcurement({{ $index }})">
                                                <i class="fa fa-times"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                @if ($supplier_id)
                                    <tr>
                                        <td colspan="5">
                                            <div class="text-center">
                                                <a class="btn btn-secondary" href="javascript:;"
                                                    wire:click="addProcurement">Tambah
                                                    Barang</a>
                                                <br>
                                                @error('procurement')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>

            </div>
            <div class="panel-footer">
                @unlessrole('guest')
                    <input type="submit" value="Simpan" class="btn btn-success" />
                @endunlessrole
                <a href="/pengadaan" class="btn btn-danger m-r-3">Batal</a>
            </div>
        </form>
    </div>
</div>
