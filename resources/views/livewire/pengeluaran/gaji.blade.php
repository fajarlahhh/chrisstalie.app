<div>
    @section('title', 'Pengeluaran Gaji')

    @section('breadcrumb')
        <li class="breadcrumb-item ">Pengeluaran</li>
        <li class="breadcrumb-item active">{{ !$data->exists ? 'Tambah Gaji' : 'Edit Gaji' }}</li>
    @endsection

    <h1 class="page-header">Pengeluaran <small>{{ !$data->exists ? 'Tambah Gaji' : 'Edit Gaji' }}</small>
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
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="w-10px">No.</th>
                                <th>Nama Pegawai</th>
                                <th>Gaji Pokok</th>
                                <th>Tunjangan</th>
                                <th>Transport</th>
                                <th class="bg-red-100">BPJS Kesehatan</th>
                                @foreach ($other as $item)
                                    <th>{{ $item }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($detail as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="text-nowrap">{{ $row['name'] }}</td>
                                    <td class="text-end text-nowrap">{{ number_format($row['wages']) }}</td>
                                    <td class="text-end text-nowrap">{{ number_format($row['allowance']) }}</td>
                                    <td class="text-end text-nowrap">{{ number_format($row['transport_allowance']) }}
                                    </td>
                                    <td class="text-end bg-red-100">{{ number_format($row['bpjs_health_cost']) }}</td>
                                    @foreach (collect($otherCost)->where('id', $row['id'])->all() as $subIndex => $subRow)
                                        <td>
                                            <input class="form-control w-150px" type="number"
                                                wire:model.live="otherCost.{{ $index }}.uang_makan" />
                                        </td>
                                        <td>
                                            <input class="form-control w-150px" type="number"
                                                wire:model.live="otherCost.{{ $index }}.jasa_pelayanan" />
                                        </td>
                                        <td>
                                            <input class="form-control w-150px" type="number"
                                                wire:model.live="otherCost.{{ $index }}.bonus" />
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            <tr>
                                <th colspan="2">TOTAL</th>
                                <th class="text-end">{{ number_format(collect($detail)->sum('wages')) }}</th>
                                <th class="text-end">{{ number_format(collect($detail)->sum('allowance')) }}</th>
                                <th class="text-end">{{ number_format(collect($detail)->sum('transport_allowance')) }}</th>
                                <th class="text-end">{{ number_format(collect($detail)->sum('bpjs_health_cost')) }}</th>
                                <th class="text-end">{{ number_format(collect($otherCost)->sum('uang_makan')) }}</th>
                                <th class="text-end">{{ number_format(collect($otherCost)->sum('jasa_pelayanan')) }}</th>
                                <th class="text-end">{{ number_format(collect($otherCost)->sum('bonus')) }}</th>
                            </tr>
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
