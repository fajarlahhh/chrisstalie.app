<div>
    @section('title', 'Input Diagnosis')

    @section('breadcrumb')
        <li class="breadcrumb-item">Klinik</li>
        <li class="breadcrumb-item">Diagnosis</li>
        <li class="breadcrumb-item active">Input</li>
    @endsection

    <h1 class="page-header">Diagnosis <small>Input</small></h1>

    <x-alert />
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form </h4>
        </div>
        <form wire:submit.prevent="submit">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="note alert-primary mb-2">
                            <!-- BEGIN tab-pane -->
                            <div class="note-content">
                                <h4>Data Pasien</h4>
                                <hr>
                                @if ($data->note)
                                    <div class="mb-3">
                                        <label class="form-label">Catatan</label>
                                        <textarea class="form-control" rows="5" disabled>
                                        {{ $data->catatan }}"
                                    </textarea>
                                    </div>
                                    <hr>
                                @endif
                                <div class="mb-3">
                                    <label class="form-label">No. RM</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien_id }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->nama }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Usia</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->umur }} Tahun"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <input class="form-control" type="text"
                                        value="{{ $data->pasien->jenis_kelamin }}" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="panel panel-inverse bg-gray-100">
                            <div class="panel-heading">
                                <h4 class="panel-title">Assessment (Penilaian)</h4>
                            </div>
                            <div class="panel-body">
                                <table class="table p-0">
                                    <thead>
                                        <tr>
                                            <th class="p-0">ICD 10</th>
                                            <th class="w-5px p-0"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($diagnosis as $index => $row)
                                            <tr>
                                                <th class="p-0">
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
                                                        wire:model="diagnosis.{{ $index }}.icd10"
                                                        data-width="100%" required>
                                                        <option value="" selected hidden>-- Pilih ICD 10 --
                                                        </option>
                                                        @foreach ($dataIcd10 as $icd)
                                                            <option value="{{ $icd['id'] }}">
                                                                {{ $icd['id'] }} - {{ $icd['uraian'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </th>
                                                <th class="align-middle w-5px pt-0 pb-0 pr-0">
                                                    <button type="button" class="btn btn-danger btn-sm"
                                                        wire:click="hapusDiagnosis({{ $index }})"
                                                        wire:loading.attr="disabled">
                                                        <span wire:loading
                                                            class="spinner-border spinner-border-sm"></span>
                                                        <span wire:loading.remove>x</span>
                                                    </button>
                                                </th>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tr class="p-0">
                                        <td colspan="3" class="p-0 pt-1 pb-0 pr-0">
                                            <button type="button" class="btn btn-primary btn-sm"
                                                wire:click="tambahDiagnosis" wire:loading.attr="disabled">
                                                <span wire:loading class="spinner-border spinner-border-sm"></span>
                                                Tambah ICD 10
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                                <div class="form-group mb-3">
                                    <label for="diagnosis_banding">Diagnosis Banding (Differential Diagnosis)</label>
                                    <textarea id="diagnosis_banding" class="form-control" wire:model.defer="diagnosis_banding"
                                        placeholder="Tuliskan kemungkinan diagnosis lain yang perlu dipertimbangkan..."></textarea>
                                    @error('diagnosis_banding')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <fieldset>
                                    <legend>Plan (Rencana Tindak Lanjut)</legend>
                                    <div class="form-group mb-3">
                                        <label for="rencana_terapi">Rencana Terapi / Tindakan</label>
                                        <textarea id="rencana_terapi" class="form-control" wire:model.defer="rencana_terapi"
                                            placeholder="Tuliskan resep obat, tindakan medis, atau anjuran terapi..."></textarea>
                                        @error('rencana_terapi')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="rencana_pemeriksaan">Rencana Pemeriksaan Penunjang</label>
                                        <textarea id="rencana_pemeriksaan" class="form-control" wire:model.defer="rencana_pemeriksaan"
                                            placeholder="Contoh: Cek Darah Lengkap, Rontgen Thorax, dll."></textarea>
                                        @error('rencana_pemeriksaan')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                @role('administrator|supervisor|operator')
                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading wire:target="submit" class="spinner-border spinner-border-sm"></span>
                        Simpan
                    </button>
                @endrole
                @if ($data->diagnosis)
                    <button type="button" class="btn btn-info m-r-3" wire:loading.attr="disabled"
                        onclick="window.location.href='/klinik/tindakan/form/{{ $data->id }}'">
                        <span wire:loading wire:target="submit" class="spinner-border spinner-border-sm"></span>
                        Lanjut Tindakan
                    </button>
                @endif
                <button type="button" class="btn btn-warning m-r-3" wire:loading.attr="disabled"
                    onclick="window.location.href='/klinik/diagnosis'">
                    <span wire:loading wire:target="submit" class="spinner-border spinner-border-sm"></span>
                    Data
                </button>
            </div>
        </form>
    </div>
</div>
