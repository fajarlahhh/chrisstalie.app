<div>
    @section('title', 'Tambah PelayananDiagnosa')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pelayanan</li>
        <li class="breadcrumb-item">PelayananDiagnosa</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">PelayananDiagnosa <small>Tambah</small></h1>

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
                                    <label class="form-label">No. Hp</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->no_hp }}"
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
                                    <th>ICD 10</th>
                                    <th class="w-5px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pelayananDiagnosa as $index => $row)
                                    <tr>
                                        <th class="align-middle">{{ $index + 1 }}</th>
                                        <th>
                                            <select data-container="body" class="form-control" x-init="$($el).selectpicker({
                                                liveSearch: true,
                                                width: 'auto',
                                                size: 10,
                                                container: 'body',
                                                style: '',
                                                showSubtext: true,
                                                styleBase: 'form-control'
                                            })"
                                                wire:model="pelayananDiagnosa.{{ $index }}.icd10" data-width="100%" required>
                                                <option value="" selected hidden>-- Pilih ICD 10 --</option>
                                                @foreach ($dataIcd10 as $icd)
                                                    <option value="{{ $icd['id'] }}">
                                                        {{ $icd['uraian'] }} ({{ $icd['id'] }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th class="align-middle">
                                            <a href="javascript:;" class="btn btn-danger btn-sm"
                                                wire:click="deletePelayananDiagnosa({{ $index }})">x</a>
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <a href="javascript:;" class="btn btn-primary btn-sm"
                                            wire:click="addPelayananDiagnosa">Tambah PelayananDiagnosa</a>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
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
