<div>
    @section('title', 'Pengeluaran Bulanan')

    @section('breadcrumb')
        <li class="breadcrumb-item ">Pengeluaran</li>
        <li class="breadcrumb-item active">{{ !$data->exists ? 'Tambah Bulanan' : 'Edit Bulanan' }}</li>
    @endsection

    <h1 class="page-header">Pengeluaran <small>{{ !$data->exists ? 'Tambah Bulanan' : 'Edit Bulanan' }}</small>
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
                    <input class="form-control" type="date" wire:model.lazy="date" />
                    @error('date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <input class="form-control" type="text" value="{{ $description }}" disabled />
                    @error('description')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="w-10px">No.</th>
                            <th>Pengeluaran</th>
                            <th class="w-200px">Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detail as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row['description'] }}</td>
                                <td class="with-btn">
                                    <input type="number" class="form-control w-200px" min="0" maxdigit="15" step="1"
                                        wire:model="detail.{{ $index }}.cost" autocomplete="off">
                                    @error('detail.' . $index . '.cost')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
