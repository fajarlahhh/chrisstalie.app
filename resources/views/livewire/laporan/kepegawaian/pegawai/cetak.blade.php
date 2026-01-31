@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Pegawai</h5>
        <hr>
    </div>
@endif
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th class="w-10px">No.</th>
            <th>ID</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>No. Hp</th>
            <th>No. BPJS</th>
            <th>Tanggal Masuk</th>
            <th>Satuan Tugas</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data as $key => $row)
            <tr>
                <td>
                    {{ ++$no }}
                </td>
                <td>{{ $row['id'] }}</td>
                <td>{{ $row['nik'] }}</td>
                <td>{{ $row['nama'] }}</td>
                <td>{{ $row['alamat'] }}</td>
                <td>{{ $row['no_hp'] }}</td>
                <td>{{ $row['no_bpjs'] }}</td>
                <td>{{ $row['tanggal_masuk'] }}</td>
                <td>{{ $row['satuan_tugas'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
