<div>
    @section('title', 'Tambah Tindakan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pelayanan</li>
        <li class="breadcrumb-item">Tindakan</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">Tindakan <small>Tambah</small></h1>

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
                                    <input class="form-control" type="text" value="{{ $data->pasien->rm }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. KTP</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->nik }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->nama }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->alamat }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->tempat_lahir }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input class="form-control" type="date" value="{{ $data->pasien->tanggal_lahir }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->jenis_kelamin }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telpon</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->no_telpon }}"
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
                        <div class="overflow-auto">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="w-5px">No.</th>
                                        <th>Tindakan</th>
                                        <th class="w-100px">Qty</th>
                                        <th class="w-400px" colspan="2">Nakes</th>
                                        <th class="w-5px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($treatment as $index => $row)
                                        <tr>
                                            <th class="align-middle">{{ $index + 1 }}</th>
                                            <th>
                                                <select data-container="body" class="form-control"
                                                    x-init="$($el).selectpicker({
                                                        liveSearch: true,
                                                        width: 'auto',
                                                        size: 10,
                                                        container: 'body',
                                                        style: '',
                                                        showSubtext: true,
                                                        styleBase: 'form-control'
                                                    })"
                                                    wire:model.lazy="treatment.{{ $index }}.action_rate_id"
                                                    wire:change="changeTarif({{ $index }})" required
                                                    data-width="100%">
                                                    <option value="" selected hidden>-- Pilih Tindakan --</option>
                                                    @foreach ($dataTarif as $actionRate)
                                                        <option value="{{ $actionRate['id'] }}"
                                                            data-subtext="{{ number_format($actionRate['harga']) }}">
                                                            {{ $actionRate['nama'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            <th>
                                                <input class="form-control w-100px" min="0" step="1"
                                                    type="number" wire:model="treatment.{{ $index }}.qty" />
                                            </th>
                                            <th>
                                                @if ($row['action_rate_id'])
                                                    @if (collect($dataTarif)->where('id', $row['action_rate_id'])->first()['porsi_nakes'] > 0)
                                                        <select data-container="body" class="form-controll w-200px"
                                                            x-init="$($el).selectpicker({
                                                                liveSearch: true,
                                                                width: 'auto',
                                                                size: 10,
                                                                container: 'body',
                                                                style: '',
                                                                showSubtext: true,
                                                                styleBase: 'form-control'
                                                            })"
                                                            wire:model="treatment.{{ $index }}.nakes_id"
                                                            data-width="100%" required>
                                                            <option value="" selected>-- Pilih Nakes --</option>
                                                            @foreach ($dataNakes as $petugas)
                                                                <option value="{{ $petugas['id'] }}">
                                                                    {{ $petugas['nama'] ?: $petugas['pegawai']['nama'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                @endif
                                            </th>
                                            <th>
                                                @if ($row['action_rate_id'])
                                                    @if (collect($dataTarif)->where('id', $row['action_rate_id'])->first()['upah_petugas'] > 0)
                                                        <select data-container="body" class="form-control w-200px"
                                                            x-init="$($el).selectpicker({
                                                                liveSearch: true,
                                                                width: 'auto',
                                                                size: 10,
                                                                container: 'body',
                                                                style: '',
                                                                showSubtext: true,
                                                                styleBase: 'form-control'
                                                            })"
                                                            wire:model="treatment.{{ $index }}.beautician_id"
                                                            data-width="100%" required>
                                                            <option value="" selected>-- Pilih Beautician --
                                                            </option>
                                                            @foreach (collect($dataNakes)->where('dokter', 0)->toArray() as $beautician)
                                                                <option value="{{ $beautician['id'] }}">
                                                                    {{ $beautician['nama'] ?: $beautician['pegawai']['nama'] }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                @endif
                                            </th>
                                            <th class="align-middle">
                                                <a href="javascript:;" class="btn btn-danger btn-sm"
                                                    wire:click="deleteTreatment({{ $index }})">x</a>
                                            </th>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <a href="javascript:;" class="btn btn-primary btn-sm"
                                                wire:click="addTreatment">Tambah Tindakan</a>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <br>
                        <div class="overflow-auto">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="w-5px">No.</th>
                                        <th>Alat & Bahan</th>
                                        <th class=" w-100px">Qty</th>
                                        <th class="w-5px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($toolsAndMaterial as $index => $row)
                                        <tr>
                                            <th class="align-middle">{{ $index + 1 }}</th>
                                            <th>
                                                <select data-container="body" class="form-control"
                                                    x-init="$($el).selectpicker({
                                                        liveSearch: true,
                                                        width: 'auto',
                                                        size: 10,
                                                        container: 'body',
                                                        style: '',
                                                        showSubtext: true,
                                                        styleBase: 'form-control'
                                                    })"
                                                    wire:model.lazy="toolsAndMaterial.{{ $index }}.goods_id"
                                                    data-width="100%">
                                                    <option value="" selected hidden>-- Pilih Tindakan --
                                                    </option>
                                                    @foreach ($dataGoods as $goods)
                                                        <option value="{{ $goods['id'] }}"
                                                            data-subtext="{{ number_format($goods['harga']) }}">
                                                            {{ $goods['nama'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </th>
                                            <th>
                                                <input class="form-control" type="number" min="0"
                                                    step="1"
                                                    wire:model="toolsAndMaterial.{{ $index }}.qty" />
                                            </th>
                                            <th class="align-middle">
                                                <a href="javascript:;" class="btn btn-danger btn-sm"
                                                    wire:click="deleteToolsAndMaterials({{ $index }})">x</a>
                                            </th>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            <a href="javascript:;" class="btn btn-primary btn-sm"
                                                wire:click="addToolsAndMaterials">Tambah Alat & Bahan</a>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
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
