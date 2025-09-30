<div>
    @section('title', 'Input Tindakan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Klinik</li>
        <li class="breadcrumb-item">Tindakan</li>
        <li class="breadcrumb-item active">Input</li>
    @endsection


    <h1 class="page-header">Tindakan <small>Input</small></h1>

    <x-alert />

    <div class="note alert-primary mb-2">
        <div class="note-content">
            <h5>Data Pasien</h5>
            <hr>
            <table class="w-100">
                <tr>
                    <td class="w-200px">No. RM</td>
                    <td class="w-10px">:</td>
                    <td>{{ $data->pasien_id }}</td>
                </tr>
                <tr>
                    <td>Nama</td>
                    <td class="w-10px">:</td>
                    <td>{{ $data->pasien->nama }}</td>
                </tr>
                <tr>
                    <td>Usia</td>
                    <td class="w-10px">:</td>
                    <td>{{ $data->pasien->umur }} Tahun</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td class="w-10px">:</td>
                    <td>{{ $data->pasien->jenis_kelamin }}</td>
                </tr>
            </table>
        </div>
    </div>
    <form wire:submit.prevent="submit">
        <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
            <div class="panel-heading ui-sortable-handle">
                <h4 class="panel-title">Form</h4>
            </div>
            <div class="panel-body">
                <table class="table table-borderless p-0">
                    <tr>
                        <td class="p-0">
                            @foreach ($tindakan as $index => $row)
                                <div class="border p-3 position-relative @if ($index > 0) mt-3 @endif">
                                    @if ($index > 0)
                                        <button type="button" class="btn btn-danger btn-xs position-absolute"
                                            style="top: 5px; right: 5px; z-index: 10;"
                                            wire:click="hapusTindakan({{ $index }})"
                                            wire:loading.attr="disabled">
                                            &nbsp;x&nbsp;
                                        </button>
                                    @endif
                                    <div class="mb-3">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-md-10">
                                                <label class="form-label">Tindakan {{ $index + 1 }}</label>
                                                <select class="form-control" x-init="$($el).selectpicker({
                                                    liveSearch: true,
                                                    width: 'auto',
                                                    size: 10,
                                                    container: 'body',
                                                    style: '',
                                                    showSubtext: true,
                                                    styleBase: 'form-control'
                                                })"
                                                    wire:model.live="tindakan.{{ $index }}.id">
                                                    <option value="" selected hidden>-- Pilih Tindakan --</option>
                                                    @foreach ($dataTindakan as $tindakan)
                                                        <option value="{{ $tindakan['id'] }}">
                                                            {{ $tindakan['nama'] }} (Rp.
                                                            {{ number_format($tindakan['tarif'], 0, ',', '.') }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('tindakan.' . $index . '.id')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label">Qty</label>
                                                <input type="number" min="1" class="form-control"
                                                    placeholder="Qty" wire:model="tindakan.{{ $index }}.qty">
                                                @error('tindakan.' . $index . '.qty')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @if ($row['biaya_jasa_dokter'] > 0)
                                        <div class="mb-3">
                                            <label class="form-label">Dokter</label>
                                            <select class="form-control"
                                                wire:model="tindakan.{{ $index }}.dokter_id">
                                                <option value="">-- Pilih Dokter --</option>
                                                @foreach (collect($dataNakes)->where('dokter', 1)->toArray() as $nakes)
                                                    <option value="{{ $nakes['id'] }}">{{ $nakes['nama'] }}</option>
                                                @endforeach
                                            </select>
                                            @error('tindakan.' . $index . '.dokter_id')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif
                                    @if ($row['biaya_jasa_perawat'] > 0)
                                        <div class="mb-3">
                                            <label class="form-label">Perawat</label>
                                            <select class="form-control"
                                                wire:model="tindakan.{{ $index }}.perawat_id">
                                                <option value="">-- Pilih Perawat --</option>
                                                @foreach (collect($dataNakes)->toArray() as $nakes)
                                                    <option value="{{ $nakes['id'] }}">{{ $nakes['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                    <div class="mb-3">
                                        <label class="form-label">Catatan</label>
                                        <textarea class="form-control" wire:model="tindakan.{{ $index }}.catatan"></textarea>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox"
                                            id="membutuhkan_inform_consent{{ $index }}"
                                            wire:model="tindakan.{{ $index }}.membutuhkan_inform_consent">
                                        <label class="form-check-label"
                                            for="membutuhkan_inform_consent{{ $index }}">
                                            Butuh Informed Consent</label>
                                    </div>
                                    <div class="form-check form-switch mb-3">
                                        <input class="form-check-input" type="checkbox"
                                            id="membutuhkan_sitemarking{{ $index }}"
                                            wire:model="tindakan.{{ $index }}.membutuhkan_sitemarking">
                                        <label class="form-check-label"
                                            for="membutuhkan_sitemarking{{ $index }}">
                                            Butuh Sitemarking</label>
                                    </div>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <button type="button" class="btn btn-primary btn-sm" wire:click="tambahTindakan"
                                wire:loading.attr="disabled">
                                <span wire:loading class="spinner-border spinner-border-sm"></span>
                                Tambah Tindakan Lainnya
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="panel-footer">
                @role('administrator|supervisor|operator')
                    <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                        Simpan
                    </button>
                @endrole
                @if ($data->tindakan->count() > 0)
                    <button type="button" class="btn btn-info m-r-3" wire:loading.attr="disabled"
                        onclick="window.location.href='/klinik/resepobat/form/{{ $data->id }}'">
                        <span wire:loading class="spinner-border spinner-border-sm"></span>
                        Lanjut Resep Obat
                    </button>
                @endif
                <button type="button" class="btn btn-warning m-r-3" wire:loading.attr="disabled"
                    onclick="window.location.href='/klinik/tindakan'">
                    <span wire:loading class="spinner-border spinner-border-sm"></span>
                    Data
                </button>
            </div>
        </div>
    </form>
    <x-alert />
</div>
