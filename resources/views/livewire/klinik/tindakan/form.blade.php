<div>
    @section('title', 'Tambah Pemeriksaan Awal')

    @section('breadcrumb')
        <li class="breadcrumb-item">Klinik</li>
        <li class="breadcrumb-item">Pemeriksaan Awal</li>
        <li class="breadcrumb-item active">Tambah</li>
    @endsection

    @section('css')<style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f4f7f6;
                color: #333;
                line-height: 1.6;
                margin: 0;
                padding: 20px;
            }

            .form-container {
                max-width: 800px;
                margin: 20px auto;
                padding: 25px;
                background-color: #ffffff;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            }

            h1 {
                text-align: center;
                color: #2c3e50;
                margin-bottom: 25px;
                font-size: 1.8em;
            }

            fieldset {
                border: 1px solid #ddd;
                border-radius: 5px;
                padding: 20px;
                margin-bottom: 20px;
            }

            legend {
                font-weight: 600;
                color: #3498db;
                padding: 0 10px;
                font-size: 1.2em;
            }

            .form-group {
                margin-bottom: 15px;
            }

            label {
                display: block;
                margin-bottom: 5px;
                font-weight: 500;
            }

            input[type="text"],
            input[type="date"],
            input[type="datetime-local"],
            textarea {
                width: 100%;
                padding: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
                font-size: 1em;
            }

            textarea {
                resize: vertical;
                min-height: 80px;
            }

            .radio-group label,
            .checkbox-group label {
                font-weight: normal;
                display: inline-block;
                margin-right: 15px;
            }

            .body-diagram-container {
                /* border: 2px dashed #ccc; */
                /* Tidak perlu border putus-putus lagi karena sudah ada gambar */
                padding: 10px;
                text-align: center;
                margin-top: 10px;
                border-radius: 5px;
                background-color: #fafafa;
                display: flex;
                /* Untuk menata gambar secara berdampingan */
                justify-content: space-around;
                /* Memberi jarak antar gambar */
                flex-wrap: wrap;
                /* Agar gambar bisa wrap ke bawah di layar kecil */
                gap: 15px;
                /* Jarak antar gambar */
            }

            .body-diagram-container img {
                max-width: 48%;
                /* Agar dua gambar bisa berdampingan */
                height: auto;
                border: 1px solid #eee;
                /* Sedikit border untuk gambar */
                border-radius: 4px;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
                flex-grow: 1;
                /* Agar gambar bisa sedikit membesar jika ada ruang */
                min-width: 250px;
                /* Lebar minimum agar tidak terlalu kecil */
            }

            @media (max-width: 600px) {
                .body-diagram-container img {
                    max-width: 100%;
                    /* Satu gambar per baris di layar kecil */
                }
            }

            .submit-btn {
                display: block;
                width: 100%;
                padding: 12px;
                background-color: #3498db;
                color: #ffffff;
                border: none;
                border-radius: 5px;
                font-size: 1.1em;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .submit-btn:hover {
                background-color: #2980b9;
            }
        </style>
    @endsection

    <h1 class="page-header">Pemeriksaan Awal <small>Tambah</small></h1>

    <x-alert />
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" role="presentation" wire:ignore>
            <a href="#default-tab-0" data-bs-toggle="tab" class="nav-link active" aria-selected="true" role="tab">
                <span class="d-sm-none">Pemeriksaan Awal</span>
                <span class="d-sm-block d-none">Pemeriksaan Awal</span>
            </a>
        </li>
        <li class="nav-item" role="presentation" wire:ignore>
            <a href="#default-tab-1" data-bs-toggle="tab" class="nav-link" aria-selected="true" role="tab">
                <span class="d-sm-none">TUG</span>
                <span class="d-sm-block d-none">Tes Up and Go</span>
            </a>
        </li>
    </ul>
    <form wire:submit.prevent="submit">
        <div class="tab-content panel rounded-0 p-3 m-0">
            <div class="tab-pane fade active show" id="default-tab-0" role="tabpanel" wire:ignore.self>

                <fieldset>
                    <legend>Data Pasien & Prosedur</legend>
                    <div class="form-group">
                        <label for="nama_pasien">Nama Pasien</label>
                        <input type="text" id="nama_pasien" name="nama_pasien"
                            placeholder="Masukkan nama lengkap pasien" required>
                    </div>
                    <div class="form-group">
                        <label for="no_rm">No. Rekam Medis</label>
                        <input type="text" id="no_rm" name="no_rm" placeholder="Contoh: 00123456" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_lahir">Tanggal Lahir</label>
                        <input type="date" id="tgl_lahir" name="tgl_lahir" required>
                    </div>
                    <div class="form-group">
                        <label for="prosedur">Nama Prosedur / Tindakan</label>
                        <textarea id="prosedur" name="prosedur" placeholder="Jelaskan nama prosedur yang akan dilakukan" required></textarea>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Proses Penandaan Lokasi</legend>
                    <div class="form-group">
                        <label for="lokasi_prosedur">Lokasi / Sisi Prosedur yang Ditandai</label>
                        <input type="text" id="lokasi_prosedur" name="lokasi_prosedur"
                            placeholder="Contoh: Lutut Kanan, Jari Telunjuk Kiri, Perut Kuadran Kanan Bawah" required>
                    </div>
                    <div class="form-group">
                        <label>Keterlibatan Pasien</label>
                        <div class="radio-group">
                            <input type="radio" id="pasien_terlibat" name="keterlibatan_pasien" value="ya"
                                checked>
                            <label for="pasien_terlibat">Ya, pasien/keluarga terlibat aktif dan mengkonfirmasi
                                lokasi</label>
                        </div>
                        <div class="radio-group">
                            <input type="radio" id="pasien_tidak_kompeten" name="keterlibatan_pasien"
                                value="tidak_kompeten">
                            <label for="pasien_tidak_kompeten">Tidak, pasien tidak sadar / tidak kompeten</label>
                        </div>
                        <div class="radio-group">
                            <input type="radio" id="pasien_menolak" name="keterlibatan_pasien" value="menolak">
                            <label for="pasien_menolak">Pasien menolak penandaan</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="petugas">Penandaan Dilakukan Oleh</label>
                        <input type="text" id="petugas" name="petugas" placeholder="Nama lengkap dan gelar petugas"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="waktu_penandaan">Tanggal & Waktu Penandaan</label>
                        <input type="datetime-local" id="waktu_penandaan" name="waktu_penandaan" required>
                    </div>

                    <div class="form-group">
                        <label>Diagram Lokasi Tubuh untuk Referensi</label>
                        <div class="body-diagram-container">
                            <img src="https://via.placeholder.com/350x500/E0F2F7/333333?text=Diagram+Tubuh+Depan"
                                alt="Diagram Tubuh Depan">
                            <img src="https://via.placeholder.com50x500/F2E0F7/333333?text=Diagram+Tubuh+Belakang"
                                alt="Diagram Tubuh Belakang">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="catatan_diagram">Catatan Terkait Penandaan pada Diagram</label>
                        <textarea id="catatan_diagram" name="catatan_diagram"
                            placeholder="Deskripsikan lokasi penandaan secara spesifik. Contoh: Tanda 'X' diberikan pada lutut kanan, bagian lateral, 5cm di atas patella."></textarea>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Pengecualian (Bila Lokasi Tidak Ditandai)</legend>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="tidak_ditandai" name="tidak_ditandai">
                        <label for="tidak_ditandai">Lokasi tidak memungkinkan untuk ditandai.</label>
                    </div>
                    <div class="form-group">
                        <label for="alasan_tidak_ditandai">Alasan (jika tidak ditandai)</label>
                        <textarea id="alasan_tidak_ditandai" name="alasan_tidak_ditandai"
                            placeholder="Contoh: Operasi pada gigi, area mukosa, bayi prematur, lesi kulit yang akan dieksisi, dll."></textarea>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Verifikasi</legend>
                    <div class="form-group">
                        <label for="verifier_name">Diverifikasi Oleh (Perawat/Dokter)</label>
                        <input type="text" id="verifier_name" name="verifier_name"
                            placeholder="Nama lengkap verifikator" required>
                    </div>
                    <div class="form-group checkbox-group">
                        <input type="checkbox" id="verifikasi_check" name="verifikasi_check" required>
                        <label for="verifikasi_check">Saya telah memverifikasi bahwa lokasi yang ditandai sesuai dengan
                            data rekam medis dan konfirmasi pasien/keluarga.</label>
                    </div>
                </fieldset>

            </div>
            <div class="tab-pane fade" id="default-tab-1" role="tabpanel" wire:ignore.self>
                <hr>
                <div>
                    @role('administrator|supervisor|operator')
                        <button type="submit" class="btn btn-success" wire:loading.attr="disabled">
                        <span wire:loading wire:target="submit" class="spinner-border spinner-border-sm"></span>
                        Simpan
                    </button>
                    @endrole
                    <a href="/klinik/pemeriksaanawal" class="btn btn-warning m-r-3">Data</a>
                </div>
            </div>
        </div>
    </form>
</div>
