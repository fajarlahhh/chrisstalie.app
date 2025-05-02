<div>
    @section('title', 'Pelunasan Pengadaan')

    @section('breadcrumb')
        <li class="breadcrumb-item ">Pelunasan Pengadaan</li>
        <li class="breadcrumb-item active">{{ !$data->exists ? 'Tambah Pelunasan Pengadaan' : 'Edit Pelunasan Pengadaan' }}
        </li>
    @endsection

    <h1 class="page-header">Pelunasan Pengadaan
        <small>{{ !$data->exists ? 'Tambah Pelunasan Pengadaan' : 'Edit Pelunasan Pengadaan' }}</small>
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
                <div class="mb-3">
                    <label class="form-label">Pengadaan</label>
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
                                {{ $row['receipt'] }} - {{ $row['description'] }}
                            </option>
                        @endforeach
                    </select>
                    @error('year')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Uraian</th>
                                <th>Biaya</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($purchase_id)
                                @foreach ($detail as $index => $row)
                                    <tr>
                                        <td>{{ $row['description'] }}</td>
                                        <td class="text-end">{{ number_format($row['cost']) }}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end">
                                        {{ number_format(collect($detail)->where('description', '!=', 'Discount')->sum('cost') - collect($detail)->where('description', 'Discount')->sum('cost')) }}
                                    </th>
                                </tr>
                            @endif
                        </tbody>
                    </table>
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
