<div>
    @section('title', 'Kasir')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pelayanan</li>
        <li class="breadcrumb-item">Kasir</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">Kasir <small>Tambah</small></h1>

    <x-alert />

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="note alert-primary mb-2">
                            <!-- BEGIN tab-pane -->
                            <div class="note-content">
                                @if ($data->note)
                                    <div class="mb-3">
                                        <label class="form-label">Catatan Pasien</label>
                                        <textarea class="form-control" rows="5" disabled>
                                            {{ $data->note }}"
                                        </textarea>
                                    </div>
                                    <hr>
                                @endif
                                <div class="mb-3">
                                    <label class="form-label">No. RM</label>
                                    <input class="form-control" type="text" value="{{ $data->patient->rm }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. KTP</label>
                                    <input class="form-control" type="text" value="{{ $data->patient->nik }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input class="form-control" type="text" value="{{ $data->patient->name }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input class="form-control" type="text" value="{{ $data->patient->address }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input class="form-control" type="text" value="{{ $data->patient->birth_place }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input class="form-control" type="date" value="{{ $data->patient->birth_date }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <input class="form-control" type="text" value="{{ $data->patient->gender }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telpon</label>
                                    <input class="form-control" type="text" value="{{ $data->patient->phone }}"
                                        disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input class="form-control" type="date" wire:model="date" />
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="w-5px">No.</th>
                                    <th>Tindakan</th>
                                    <th class="w-70px">Qty</th>
                                    <th class="w-90px">Diskon</th>
                                    <th class="w-100px">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($treatment as $index => $row)
                                    <tr>
                                        <th class="align-middle">{{ $index + 1 }}</th>
                                        <th>
                                            <select data-container="body" class="form-control"
                                                wire:model.lazy="treatment.{{ $index }}.action_rate_id" disabled
                                                data-width="100%">
                                                @foreach ($dataActionRate as $actionRate)
                                                    <option value="{{ $actionRate['id'] }}">
                                                        {{ $actionRate['name'] }} - Rp.
                                                        {{ number_format($actionRate['price']) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th>
                                            <input class="form-control" type="text" value="{{ $row['qty'] }}"
                                                disabled />
                                        </th>
                                        <th>
                                            <input class="form-control" type="number" max="100" maxlength="3"
                                                wire:model.lazy="treatment.{{ $index }}.discount" />
                                        </th>
                                        <th>
                                            <input class="form-control text-end" type="text"
                                                value="{{ number_format(($row['price'] - (($row['discount'] ?: 0) / 100) * $row['price']) * $row['qty']) }}"
                                                disabled />
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="w-5px">No.</th>
                                    <th>Alat & Bahan</th>
                                    <th class="w-70px">Qty</th>
                                    <th class="w-90px">Diskon</th>
                                    <th class="w-100px">Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($toolsAndMaterial as $index => $row)
                                    <tr>
                                        <th>{{ $index + 1 }}</th>
                                        <th>
                                            <select data-container="body" class="form-control"
                                                wire:model.lazy="toolsAndMaterial.{{ $index }}.goods_id"
                                                data-width="100%" disabled>
                                                @foreach ($dataGoods as $goods)
                                                    <option value="{{ $goods['id'] }}"
                                                        data-subtext="{{ number_format($goods['price']) }}">
                                                        {{ $goods['name'] }} - Rp.
                                                        {{ number_format($goods['price']) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('toolsAndMaterial.' . $index . '.goods_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </th>
                                        <th>
                                            <input class="form-control" type="text" value="{{ $row['qty'] }}"
                                                disabled />
                                        </th>
                                        <th>
                                            <input class="form-control" type="number" maxlength="3" max="100"
                                                wire:model.lazy="toolsAndMaterial.{{ $index }}.discount" />
                                        </th>
                                        <th>
                                            <input class="form-control text-end" type="text"
                                                value="{{ number_format(($row['price'] - (($row['discount'] ?: 0) / 100) * $row['price']) * $row['qty']) }}"
                                                disabled />
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mb-3">
                            <label class="form-label">Biaya Admin</label>
                            <input class="form-control" type="text" wire:model.lazy="adminFee" />
                            @error('adminFee')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="note-content">
                            <div class="mb-3">
                                <label class="form-label">Total Tagihan</label>
                                <input class="form-control text-end" type="text"
                                    value="{{ number_format(($adminFee ?: 0) + collect($treatment)->sum(fn($q) => ($q['price'] - (($q['discount'] ?: 0) / 100) * $q['price']) * $q['qty']) + collect($toolsAndMaterial)->sum(fn($q) => ($q['price'] - (($q['discount'] ?: 0) / 100) * $q['price']) * $q['qty'])) }}"
                                    disabled />
                                @error('adminFee')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="note alert-success mb-2">
                            <div class="note-content">
                                <h4>Pembayaran</h4>
                                <hr>
                                {{-- <div class="mb-3">
                                    <label class="form-label">Jenis Bayar</label>
                                    <select class="form-control" wire:model.lazy="type" data-width="100%">
                                        <option hidden selected>-- Pilih Jenis Bayar --</option>
                                        @foreach (\App\Enums\PaymentEnum::cases() as $item)
                                            <option value="{{ $item->value }}">{{ $item->label() }}</option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div> --}}
                                @if ($type == 'Cash')
                                    <div class="mb-3">
                                        <label class="form-label">Cash</label>
                                        <input class="form-control" type="number" wire:model.live="cash" />
                                        @error('cash')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Uang Kembali</label>
                                        <input class="form-control text-end" type="text" disabled
                                            value="{{ number_format(($cash ?: 0) - (($adminFee ?: 0) + collect($treatment)->sum(fn($q) => ($q['price'] - (($q['discount'] ?: 0) / 100) * $q['price']) * $q['qty']) + collect($toolsAndMaterial)->sum(fn($q) => ($q['price'] - (($q['discount'] ?: 0) / 100) * $q['price']) * $q['qty']))) }}" />
                                        @error('remainder')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                @role('administrator|supervisor|operator')
                    <input type="submit" value="Simpan" class="btn btn-success" />
                @endrole
                <a href="/pelayanan/pendaftaran/data" class="btn btn-warning m-r-3">Data</a>
            </div>
        </form>
    </div>
</div>
