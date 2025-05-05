<div>
    @section('title', 'Tambah Barang Masuk')

    @section('breadcrumb')
        <li class="breadcrumb-item">Apotek</li>
        <li class="breadcrumb-item">Barang Masuk</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">Barang Masuk <small>Tambah</small></h1>

    <x-alert />

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit">
            <div class="panel-body">
                <div class="note alert-info mb-0">
                    <div class="note-content">
                        <h4>Data Pengadaan</h4>
                        <hr>
                        <div class="mb-3">
                            <label class="form-label">Cari Data</label>
                            <select data-container="body" class="form-control" x-init="$($el).selectpicker({
                                liveSearch: true,
                                width: 'auto',
                                size: 10,
                                container: 'body',
                                style: '',
                                showSubtext: true,
                                styleBase: 'form-control'
                            })"
                                wire:model.live="purchase_id" data-width="100%">
                                <option selected value="">-- Pilih Data Pengadaan --</option>
                                @foreach ($purchaseData as $row)
                                    <option value="{{ $row['id'] }}"
                                        data-subtext="{{ collect($row['purchase_detail'])->pluck('goods_name_qty')->join(',') }}">
                                        {{ $row['receipt'] }} - {{ $row['uraian'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('purchase_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengadaan</label>
                            <input class="form-control" type="text" value="{{ $purchase ? $purchase['date'] : '' }}"
                                disabled />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Supplier</label>
                            <input class="form-control" type="text"
                                value="{{ $purchase ? $purchase['supplier'] : '' }}" disabled />
                        </div>
                    </div>
                </div>
                <br>
                <div class="mb-3">
                    <label class="form-label">Tanggal Masuk</label>
                    <input class="form-control" type="date" wire:model="date" />
                    @error('date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input class="form-control" type="text" wire:model="uraian" />
                    @error('uraian')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="note alert-secondary mb-0">
                    <div class="note-content table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Barang/Item</th>
                                    <th class="w-100px">Satuan</th>
                                    <th class="w-100px">Pengadaan</th>
                                    <th class="w-100px">Sisa</th>
                                    <th class="w-100px">Stok Masuk</th>
                                    <th class="w-150px">No. Batch</th>
                                    <th class="w-150px">Tanggal Kadaluarsa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($goods as $index => $row)
                                    <tr>
                                        <td class="with-btn">
                                            <select class="form-control">
                                                <option value="{{ $row['goods_id'] }}">
                                                    {{ $row['goods'] }}
                                                </option>
                                            </select>
                                        </td>
                                        <td class="with-btn">
                                            <input type="text" class="form-control w-100px"
                                                value="{{ $row['satuan'] }}" autocomplete="off" disabled>
                                        </td>
                                        <td class="with-btn">
                                            <input type="text" class="form-control w-100px"
                                                value="{{ $row['qty'] }}" autocomplete="off" disabled>
                                        </td>
                                        <td class="with-btn">
                                            <input type="text" class="form-control w-100px"
                                                value="{{ $row['remaining'] }}" autocomplete="off" disabled>
                                        </td>
                                        <td class="with-btn">
                                            @if ($row['remaining'] > 0)
                                                <input type="number" class="form-control w-100px" min="0"
                                                    step="1" wire:model="goods.{{ $index }}.stok_in"
                                                    autocomplete="off">
                                                @error('goods.' . $index . '.stok_in')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            @endif
                                        </td>
                                        <td class="with-btn">
                                            <input type="text" class="w-150px form-control"
                                                wire:model="goods.{{ $index }}.batch_number" autocomplete="off">
                                            @error('goods.' . $index . '.batch_number')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="with-btn">
                                            <input type="date" class="w-150px form-control"
                                                wire:model="goods.{{ $index }}.expired_date" autocomplete="off">
                                            @error('goods.' . $index . '.expired_date')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                @unlessrole('guest')
                    <input type="submit" value="Simpan" class="btn btn-success" />
                @endunlessrole
                <a href="{{ $previous }}" class="btn btn-danger" wire:ignore>Batal</a>
            </div>
        </form>
    </div>
</div>
