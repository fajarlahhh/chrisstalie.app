<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Pemesanan Chrisstalie</title>
</head>

<body>
    <div class="container w-600px fs-12px">

        <div class="row">
            <div class="col-12 text-center fw-bold">
                <img src="/assets/img/kop_surat.png" alt="logo" class="w-100"><br>
                <h4>SURAT PESANAN<br><small class="mt-0">{{ $data->nomor }}</small>
                </h4><br><br>
            </div>
            <div class="col-6">Kepada Yth :<br>
                Pimpinan {{ $data->supplier->nama }}<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{ $data->supplier->alamat }}
            </div>
            <div class="col-6 text-end">
                Praya,
                {{ \Carbon\Carbon::parse($data->pengadaanPemesananVerifikasi->waktu_verifikasi)->format('d F Y') }}<br>
            </div>
            <div class="col-12">
                <br>
                <p>Mohon dikirim barang/obat-obatan untuk keperluan klinik sesuai dengan daftar berikut :</p>
                <table class="table table-bordered fs-10px">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Barang</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Qty</th>
                            <th>Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data->pengadaanPemesananDetail as $detail)
                            <tr>
                                <td class="w-10px">{{ $loop->iteration }}</td>
                                <td>{{ $detail->barangSatuan->barang->nama }}</td>
                                <td class="w-100px">{{ $detail->barangSatuan->nama }}</td>
                                <td class="text-end w-100px">{{ number_format($detail->harga_beli) }}</td>
                                <td class="text-center w-50px">{{ $detail->qty }}</td>
                                <td class="text-end w-100px">{{ number_format($detail->harga_beli * $detail->qty) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Total</th>
                            <th class="text-end">
                                {{ number_format($data->pengadaanPemesananDetail->sum(fn($q) => $q->harga_beli * $q->qty)) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
                <strong>Estimasi Kedatangan:</strong>
                {{ \Carbon\Carbon::parse($data->tanggal_estimasi_kedatangan)->format('d F Y') }}
                <br>
                <br>
            </div>
        </div>
        <div class="col-12 text-center">
            Apoteker Instalasi Farmasi Klinik<br>
            <span style="position: relative; display: inline-block; width: 90px; height: 70px;">
                <img src="{{ Storage::url($data->pengadaanPemesananVerifikasi->pengguna->kepegawaianPegawai->tanda_tangan) }}"
                    alt="ttd" class="w-100px" style="position: absolute; top: 10px; left: 0; z-index: 1;">
                <img src="{{ asset('assets/img/stempel.png') }}" alt="stempel" class="w-150px"
                    style="position: absolute; top: -10px; left: -80px; z-index: 222; opacity: 0.8;">
            </span>
            <br>
            <br>
            <u>{{ $data->pengadaanPemesananVerifikasi->pengguna->nama }}</u><br>
            SIPA : {{ $data->pengadaanPemesananVerifikasi->pengguna->kepegawaianPegawai->sipa }}<br>
        </div>
    </div>
</body>

</html>
