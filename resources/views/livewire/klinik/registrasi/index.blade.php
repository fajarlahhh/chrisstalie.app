<div>
    @section('title', 'Registrasi')

    @section('breadcrumb')
        <li class="breadcrumb-item">Klinik</li>
        <li class="breadcrumb-item">Registrasi</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    <h1 class="page-header">Registrasi</h1>

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
                                <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link active"
                                    wire:click="resetPatient" wire:ignore.self>
                                    <span class="d-sm-none">Pasien Baru</span>
                                    <span class="d-sm-block d-none">Pasien Baru</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#default-tab-2" data-bs-toggle="tab" class="nav-link" wire:click="resetPatient"
                                    wire:ignore.self>
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
                                    <input class="form-control" type="text" wire:model="nik" />
                                    @error('nik')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input class="form-control" type="text" wire:model="name" />
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input class="form-control" type="text" wire:model="address" />
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input class="form-control" type="text" wire:model="birth_place" />
                                    @error('birth_place')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input class="form-control" type="date" wire:model="birth_date" />
                                    @error('birth_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select data-container="body" class="form-control " wire:model="gender"
                                        data-width="100%">
                                        <option selected>-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telpon</label>
                                    <input class="form-control" type="text" wire:model="phone" />
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!-- END tab-pane -->
                            <!-- BEGIN tab-pane -->
                            <div class="tab-pane fade" id="default-tab-2" wire:ignore.self>
                                <h4 class="mt-10px">Data Pasien</h4>
                                <hr>
                                @if (!$patient_id)
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
                                                    url: '/search/patient',
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
                                                $wire.set('patient_id', $($el).val());
                                            });
                                            
                                            function format(data) {
                                                if (!data.id) {
                                                    return data.text;
                                                }
                                                var $data = $('<table><tr><th>No. RM</th><th>:</th><th>' + data.rm + '</th></tr>' +
                                                    '<tr><th>No. KTP</th><th>:</th><th>' + data.nik + '</th></tr>' +
                                                    '<tr><th>Nama</th><th>:</th><th>' + data.name + '</th></tr>' +
                                                    '<tr><th>Alamat</th><th>:</th><th>' + data.address + '</th></tr></table>');
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
                                        @if (!$patient_id) disabled @endif />
                                    @error('rm')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. KTP</label>
                                    <input class="form-control" type="text" wire:model="nik"
                                        @if ($nik) disabled @endif
                                        @if (!$patient_id) disabled @endif />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama</label>
                                    <input class="form-control" type="text" wire:model="name"
                                        @if ($name) disabled @endif
                                        @if (!$patient_id) disabled @endif />
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <input class="form-control" type="text" wire:model="address"
                                        @if ($address) disabled @endif
                                        @if (!$patient_id) disabled @endif />
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tempat Lahir</label>
                                    <input class="form-control" type="text" wire:model="birth_place"
                                        @if ($birth_place) disabled @endif
                                        @if (!$patient_id) disabled @endif />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <input class="form-control" type="date" wire:model="birth_date"
                                        @if ($birth_date) disabled @endif
                                        @if (!$patient_id) disabled @endif />
                                    @error('birth_date')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <input class="form-control" type="text" wire:model="gender"
                                        @if ($gender) disabled @endif
                                        @if (!$patient_id) disabled @endif />
                                    @error('gender')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">No. Telpon</label>
                                    <input class="form-control" type="text" wire:model="phone"
                                        @if ($phone) disabled @endif
                                        @if (!$patient_id) disabled @endif />
                                </div>
                            </div>
                            <!-- END tab-pane -->
                        </div>
                        <!-- END tab-content -->
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tanggal</label>
                            <input class="form-control" type="date" wire:model="date" />
                            @error('date')
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
                                wire:model="practitioner_id" data-width="100%">
                                <option selected value="">-- Pilih Dokter --</option>
                                @foreach ($practitionerData as $row)
                                    <option value="{{ $row['id'] }}" data-subtext="{{ $row['doctor'] }}">
                                        {{ $row['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('practitioner_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <textarea class="form-control" wire:model="description" rows="5"></textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                @role('administrator|supervisor|operator')
                    <input wire:loading.remove type="submit" value="Simpan" class="btn btn-success" />
                @endrole
                <a href="/klinik/registrasi/data" class="btn btn-warning m-r-3">Data</a>
                <a href="javascript:;" wire:click="resetPatient" class="btn btn-secondary m-r-3">Reset</a>
            </div>
        </form>
    </div>
</div>
