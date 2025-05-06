<div>
    @section('title', 'Tambah Pendaftaran')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pelayanan</li>
        <li class="breadcrumb-item">Pendaftaran</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">Pendaftaran <small>Tambah</small></h1>

    <x-alert />

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">
            <h4 class="panel-title">Form</h4>
        </div>
        <form wire:submit.prevent="submit">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="nav nav-tabs bg-gray-100">
                            <li class="nav-item">
                                <a href="#default-tab-1" data-bs-toggle="tab"
                                    class="nav-link {{ $data->exists ? ($data->baru ? 'active' : 'disabled') : 'active' }}"
                                    wire:click="resetPasien" wire:ignore.self>
                                    <span class="d-sm-none">Pasien Baru</span>
                                    <span class="d-sm-block d-none">Pasien Baru</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#default-tab-2" data-bs-toggle="tab"
                                    class="nav-link {{ $data->exists ? ($data->baru ? 'disabled' : 'active') : '' }}"
                                    wire:click="resetPasien" wire:ignore.self>
                                    <span class="d-sm-none">Pasien Lama</span>
                                    <span class="d-sm-block d-none">Pasien Lama</span>
                                </a>
                            </li>
                        </ul>
                        <!-- END nav-tabs -->
                        <!-- BEGIN tab-content -->
                        <div class="tab-content panel rounded-0 p-3 m-0 bg-gray-100">
                            <!-- BEGIN tab-pane -->
                            <div class="tab-pane fade active show" id="default-tab-1" wire:ignore.self>
                                <h4 class="mt-10px">Data Pasien</h4>
                                <hr>
                                <div class="mb-3">
                                    <label class="form-label">No. KTP</label>
                                    <input class="form-control" type="number" minlength="16" wire:model="nik"
                                        @if ($data->exists) disabled @endif />
                                    @error('nik')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input class="form-control" type="text" wire:model="nama"
                                        @if ($data->exists) disabled @endif />
                                    @error('nama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input class="form-control" type="text" wire:model="alamat"
                                        @if ($data->exists) disabled @endif />
                                    @error('alamat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input class="form-control" type="text" wire:model="tempat_lahir"
                                        @if ($data->exists) disabled @endif />
                                    @error('tempat_lahir')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input class="form-control" type="date" wire:model="tanggal_lahir"
                                        @if ($data->exists) disabled @endif />
                                    @error('tanggal_lahir')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select data-container="body" class="form-control " wire:model="jenis_kelamin"
                                        data-width="100%" @if ($data->exists) disabled @endif>
                                        <option selected>-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    @error('jenis_kelamin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Hp</label>
                                    <input class="form-control" type="text" wire:model="no_hp"
                                        @if ($data->exists) disabled @endif />
                                    @error('no_hp')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- END tab-pane -->
                            <!-- BEGIN tab-pane -->
                            <div class="tab-pane fade" id="default-tab-2" wire:ignore.self>
                                <h4 class="mt-10px">Data Pasien</h4>
                                <hr>
                                @if (!$pasien_id)
                                    <div class="mb-3">
                                        <label class="form-label">Cari Pasien</label>
                                        <div wire:ignore>
                                            <select class="form-control" x-init="$($el).select2({
                                                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
                                                dropdownAutoWidth: true,
                                                templateResult: format,
                                                minimumInputLength: 3,
                                                dataType: 'json',
                                                ajax: {
                                                    url: '/search/pasien',
                                                    data: function(params) {
                                                        var query = {
                                                            search: params.term
                                                        }
                                                        return query;
                                                    },
                                                    processResults: function(data, params) {
                                                        return {
                                                            results: data,
                                                        };
                                                    },
                                                    cache: true
                                                }
                                            });
                                            
                                            $($el).on('change', function(element) {
                                                $wire.set('pasien_id', $($el).val());
                                            });
                                            
                                            function format(data) {
                                                if (!data.id) {
                                                    return data.text;
                                                }
                                                var $data = $('<table><tr><th>No. RM</th><th>:</th><th>' + data.rm + '</th></tr>' +
                                                    '<tr><th>No. KTP</th><th>:</th><th>' + data.nik + '</th></tr>' +
                                                    '<tr><th>Nama</th><th>:</th><th>' + data.nama + '</th></tr>' +
                                                    '<tr><th>Alamat</th><th>:</th><th>' + data.alamat + '</th></tr></table>');
                                                return $data;
                                            }">
                                            </select>
                                        </div>
                                    </div>
                                @endif
                                <div class="mb-3">
                                    <label class="form-label">No. RM</label>
                                    <input class="form-control" type="text" wire:model="rm"
                                        @if ($rm) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                    @error('rm')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. KTP</label>
                                    <input class="form-control" type="text" wire:model="nik"
                                        @if ($nik) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input class="form-control" type="text" wire:model="nama"
                                        @if ($nama) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                    @error('nama')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input class="form-control" type="text" wire:model="alamat"
                                        @if ($alamat) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                    @error('alamat')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input class="form-control" type="text" wire:model="tempat_lahir"
                                        @if ($tempat_lahir) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input class="form-control" type="date" wire:model="tanggal_lahir"
                                        @if ($tanggal_lahir) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                    @error('tanggal_lahir')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <input class="form-control" type="text" wire:model="jenis_kelamin"
                                        @if ($jenis_kelamin) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                    @error('jenis_kelamin')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Hp</label>
                                    <input class="form-control" type="text" wire:model="no_hp"
                                        @if ($no_hp) disabled @endif
                                        @if (!$pasien_id) disabled @endif />
                                </div>
                            </div>
                            <!-- END tab-pane -->
                        </div>
                        <!-- END tab-content -->
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input class="form-control" type="date" min="{{ date('Y-m-d') }}"
                                wire:model="tanggal" />
                            @error('tanggal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Dokter</label>
                            <select data-container="body" class="form-control" x-init="$($el).selectpicker({
                                liveSearch: true,
                                width: 'auto',
                                size: 10,
                                container: 'body',
                                style: '',
                                showSubtext: true,
                                styleBase: 'form-control'
                            })"
                                wire:model="nakes_id" data-width="100%">
                                <option selected value="">-- Pilih Dokter --</option>
                                @foreach ($dataNakes as $row)
                                    <option value="{{ $row['id'] }}" data-subtext="{{ $row['dokter'] }}">
                                        {{ $row['nama'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('nakes_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan</label>
                            <textarea class="form-control" wire:model="catatan" rows="5"></textarea>
                            @error('catatan')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
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
