<div>
    @section('title', 'Informasi Pasien')

    @section('breadcrumb')
        <li class="breadcrumb-item active">Informasi Pasien</li>
    @endsection

    <h1 class="page-header">Informasi Pasien</h1>

    <x-alert />

    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <!-- begin panel-heading -->
        <div class="panel-heading ui-sortable-handle">

            <h4 class="panel-title">Form</h4>
        </div>
        <div class="panel-body">
            <div class="mb-3">
                <label class="form-label">Cari Data</label>
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
                            '<tr><th>Nama</th><th>:</th><th>' + data.name + '</th></tr>' +
                            '<tr><th>Alamat</th><th>:</th><th>' + data.alamat + '</th></tr></table>');
                        return $data;
                    }">
                    </select>
                </div>
            </div>
            <div>
                @if ($pasien_id)
                    <div class="row">
                        <div class="col-md-4">
                            <table class="table table-bordered">
                                <tr>
                                    <th>IHS</th>
                                    <td>{{ $pasien->ihs }}</td>
                                </tr>
                                <tr>
                                    <th>No. RM</th>
                                    <td>{{ $pasien->rm }}</td>
                                </tr>
                                <tr>
                                    <th>No. KTP</th>
                                    <td>{{ $pasien->nik }}</td>
                                </tr>
                                <tr>
                                    <th>Nama</th>
                                    <td>{{ $pasien->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td>{{ $pasien->jenis_kelamin }}</td>
                                </tr>
                                <tr>
                                    <th>Tgl. Lahir</th>
                                    <td>{{ $pasien->tanggal_lahir }}</td>
                                </tr>
                                <tr>
                                    <th>Alamat</th>
                                    <td>{{ $pasien->alamat }}</td>
                                </tr>
                                <tr>
                                    <th>No. Telp</th>
                                    <td>{{ $pasien->no_hp }}</td>
                                </tr>
                                <tr>
                                    <th>Tgl. Registrasi</th>
                                    <td>{{ $pasien->tanggal_daftar }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-8">
                            <div class="alert alert-info">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>PelayananDiagnosa</th>
                                        <th>PelayananTindakan</th>
                                    </tr>
                                    @foreach ($pasien->pendaftaran as $pendaftaran)
                                        <tr>
                                            <td>{{ substr($pendaftaran->created_at, 0, 10) }}</td>
                                            <td>
                                                <ul>
                                                    @foreach ($pendaftaran->pelayananDiagnosa as $pelayananDiagnosa)
                                                        <li>{{ $pelayananDiagnosa->icd10?->code }} -
                                                            {{ $pelayananDiagnosa->icd10?->uraian }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                            <td>
                                                <ul>
                                                    @foreach ($pendaftaran->pelayananTindakan as $pelayananTindakan)
                                                        <li>{{ $pelayananTindakan->tarif->nama }} ({{ $pelayananTindakan->qty }} x)
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                                <hr>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Obat</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pasien->sales as $sales)
                                            <tr>
                                                <td>{{ $sales->created_at }}</td>
                                                <td>
                                                    <ul>
                                                        @foreach ($sales->saleDetail as $saleDetail)
                                                            <li>{{ $saleDetail->goods->nama }} ({{ $saleDetail->qty }}
                                                                {{ $saleDetail->goods->satuan }})</li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
