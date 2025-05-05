<div>
    @section('title', 'Gaji Gaji')

    @section('breadcrumb')
        <li class="breadcrumb-item ">Gaji</li>
        <li class="breadcrumb-item active">{{ !$data->exists ? 'Tambah Gaji' : 'Edit Gaji' }}</li>
    @endsection

    <h1 class="page-header">Gaji <small>{{ !$data->exists ? 'Tambah Gaji' : 'Edit Gaji' }}</small>
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
                    <label class="form-label">Bulan</label>
                    <select wire:model.lazy="month" class="form-control">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ sprintf('%02s', $i) }}">
                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                        @endfor
                    </select>
                    @error('month')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Tahun</label>
                    <select wire:model.lazy="year" class="form-control">
                        @for ($i = 2023; $i <= date('Y'); $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    @error('year')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Pegawai</label>
                    <select class="form-control" x-init="$($el).selectpicker({
                        liveSearch: true,
                        size: 10,
                        container: 'body',
                        style: '',
                        showSubtext: true,
                        styleBase: 'form-control'
                    })" wire:model.live="pegawai_id">
                        <option value="" selected hidden>-- Pilih Pegawai --</option>
                        @foreach ($pegawaiData as $pegawai)
                            <option value="{{ $pegawai['id'] }}">
                                {{ $pegawai['nama'] }}
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
                            @if ($pegawai_id)
                                @foreach ($detail as $index => $row)
                                    <tr>
                                        <td>{{ $row['jenis'] }}</td>
                                        <td>
                                            <input class="form-control" type="number"
                                                wire:model.live="detail.{{ $index }}.cost" />
                                        </td>
                                    </tr>
                                @endforeach
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
