<div>
    @section('title', 'Verifikasi')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pengadaan</li>
        <li class="breadcrumb-item">Verifikasi</li>
        <li class="breadcrumb-item active">Form</li>
    @endsection

    <h1 class="page-header">Verifikasi <small>Pengadaan</small></h1>

    <x-alert />

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit">
            <div class="panel-body">
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-control" wire:model="deskripsi" disabled></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select class="form-control" wire:model.live="status">
                        <option value="Disetujui">Disetujui</option>
                        <option value="Ditolak">Ditolak</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="note alert-secondary mb-3">
                    <div class="note-content table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr>
                                    <th>Barang</th>
                                    <th class="w-100px">Qty</th>
                                    <th class="w-100px">Qty Disetujui</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barang as $index => $row)
                                    <tr>
                                        <td class="with-btn">
                                            <input type="text" class="form-control"
                                                wire:model="barang.{{ $index }}.nama" autocomplete="off"
                                                disabled>
                                            @error('barang.' . $index . '.nama')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="with-btn">
                                            <input type="number" class="form-control w-100px" min="0"
                                                step="1" min="0" max="100"
                                                wire:model="barang.{{ $index }}.qty" autocomplete="off" disabled>
                                            @error('barang.' . $index . '.qty')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="with-btn">
                                            <input type="number" class="form-control w-200px" min="0"
                                                step="1" min="0" max="100"
                                                wire:model.lazy="barang.{{ $index }}.qty_disetujui"
                                                autocomplete="off" @if ($status == 'Ditolak') disabled @endif>
                                            @error('barang.' . $index . '.qty_disetujui')
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
                @role('administrator|supervisor|operator')
                    <input type="submit" value="Simpan" class="btn btn-success" />
                @endrole
                <a href="{{ $previous }}" class="btn btn-danger" wire:ignore>Batal</a>
            </div>
        </form>
    </div>
    <x-modal.cetak judul='Nota' />
</div>
