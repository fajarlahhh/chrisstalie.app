@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Jadwal Shift</h5>
        <hr>
    </div>
    <br>
    <table>
        <tr>
            <th class="w-100px">Periode</th>
            <th class="w-10px">:</th>
            <td>{{ $bulan }}</td>
        </tr>
    </table>
@endif
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">Pegawai</th>
            <th rowspan="2">Jadwal Shift</th>
        </tr>
        <tr>
            <th>Sakit</th>
            <th>Izin</th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 0;
        @endphp
        @foreach ($data as $key => $row)
            <tr>
                <td>{{ ++$no }}</td>
                <td>{{ $row['nama'] }}</td>
                <td>{{ count($row['absensi']) }}</td>
                <td>{{ collect($row['absensi'])->whereNotNull('masuk')->count() }}</td>
                <td>{{ collect($row['absensi'])->whereNotNull('masuk')->where('jam_masuk', '>', 'masuk')->count() }}
                </td>
                <td>{{ collect($row['absensi'])->whereNull('masuk')->count() }}</td>
                <td>{{ collect($row['absensi'])->where('izin', 'Sakit')->count() }}</td>
                <td>{{ collect($row['absensi'])->where('izin', 'Izin')->count() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
