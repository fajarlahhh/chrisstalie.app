<div>
    @section('title', 'Tambah Pengadaan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Apotek</li>
        <li class="breadcrumb-item">Pengadaan</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">Pengadaan <small>Tambah</small></h1>

    <x-alert />

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">

            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit">
            <div class="panel-body">
                <div class="mb-3">
                    <label class="form-label">No. Bukti</label>
                    <input class="form-control" type="text" wire:model="receipt" required />
                    @error('receipt')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Tanggal</label>
                    <input class="form-control" type="date" wire:model="date" required />
                    @error('date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input class="form-control" type="text" wire:model="uraian" required />
                    @error('uraian')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="note alert-primary mb-2">
                    <div class="note-content">
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select data-container="body" class="form-control" wire:model.live="status"
                                data-width="100%">
                                <option selected value="Jatuh Tempo">Jatuh Tempo</option>
                                <option value="Lunas">Lunas</option>
                                <option value="Opname">Opname</option>
                            </select>
                        </div>
                        @if ($status != 'Opname')
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
                                })"
                                    wire:model="supplier_id" data-width="100%" required>
                                    <option selected value="">-- Pilih Supplier --</option>
                                    @foreach ($supplierData as $row)
                                        <option value="{{ $row['id'] }}"
                                            data-subtext="{{ $row['konsinyasi'] == 1 ? 'Konsinyasi' : '' }}">
                                            {{ $row['nama'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('supplier_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                        @if ($status == 'Jatuh Tempo')
                            <div class="mb-3">
                                <label class="form-label">Tanggal Jatuh Tempo</label>
                                <input class="form-control" type="date" wire:model="due_date" required />
                                @error('due_date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    </div>
                </div>
                <div class="note alert-secondary mb-0">
                    <div class="note-content table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Barang/Item</th>
                                    <th class="w-100px">Qty</th>
                                    <th class="w-150px">Harga</th>
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
                                                        data-subtext="{{ $subRow['satuan'] }}">
                                                        {{ $subRow['nama'] . ' (' . $subRow['type'] . ')' }}
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
                                            <input type="number" class="form-control w-150px" min="0"
                                                step="1" wire:model="procurement.{{ $index }}.harga"
                                                autocomplete="off">
                                            @error('procurement.' . $index . '.harga')
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
                                <tr>
                                    <td class="text-end">&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
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
                            </tfoot>
                        </table>
                    </div>
                </div>
                <br>
                <div class="mb-3">
                    <label class="form-label">Diskon</label>
                    <input class="form-control" type="number" wire:model="discount" required />
                    @error('discount')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">PPN</label>
                    <input class="form-control" type="number" wire:model="ppn" required />
                    @error('ppn')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
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
