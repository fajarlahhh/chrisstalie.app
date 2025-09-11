<div>
    @section('title', 'Tambah Pemeriksaan Awal')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pelayanan</li>
        <li class="breadcrumb-item">Pemeriksaan Awal</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">Pemeriksaan Awal <small>Tambah</small></h1>

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
                                    <label class="form-label">Usia</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->umur }} Tahun"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->jenis_kelamin }}"
                                        disabled />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telpon</label>
                                    <input class="form-control" type="text" value="{{ $data->pasien->no_hp }}"
                                        disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label class="form-label">Keluhan Pasien</label>
                            <textarea class="form-control" wire:model="keluhan" rows="3" required></textarea>
                            @error('keluhan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <hr>
                        <div class="note alert-secondary mb-2">
                            <div class="note-content">
                                <h3>Pemeriksaan Fisik</h3>
                                <div class="row">
                                    @foreach ($pemeriksaanFisik as $key => $row)
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ $key }}</label>
                                                <input class="form-control" type="text"
                                                    wire:model="pemeriksaanFisik.{{ $key }}" />
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="note alert-secondary mb-2">
                            <div class="note-content">
                                <h3>Tanda-Tanda Vital</h3>
                                <div class="row">
                                    @foreach ($pemeriksaanTtv as $key => $row)
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ $key }}</label>
                                                @if ($key != 'Fungsi Penciuman' && $key != 'Kesadaran')
                                                    <input class="form-control" type="number" required
                                                        wire:model="pemeriksaanTtv.{{ $key }}" />
                                                @endif
                                                @if ($key == 'Fungsi Penciuman')
                                                    <select data-container="body" class="form-control"
                                                        wire:model="pemeriksaanTtv.{{ $key }}" data-width="100%">
                                                        <option value="Normal">Normal</option>
                                                        <option value="Tidak Normal">Tidak Normal</option>
                                                    </select>
                                                @endif
                                                @if ($key == 'Kesadaran')
                                                    <select data-container="body" class="form-control"
                                                        wire:model="pemeriksaanTtv.{{ $key }}" data-width="100%">
                                                        <option value="01">Compos mentis</option>
                                                        <option value="02">Somnolence</option>
                                                        <option value="03">Sopor</option>
                                                        <option value="04">Coma</option>
                                                    </select>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                @role('administrator|supervisor|operator')
                    <input type="submit" value="Simpan" class="btn btn-success" />
                @endrole
                <a href="/pelayanan/pemeriksaanawal" class="btn btn-warning m-r-3">Data</a>
            </div>
        </form>
    </div>
</div>
