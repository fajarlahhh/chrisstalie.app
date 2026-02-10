@if ($cetak)
    <div class="w-100 text-center">
        <img src="/assets/img/login.png" class="w-200px" alt="" />
        <br>
        <br>
        <h5>Laporan Laba Rugi</h5>
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

<table class="table table-borderless table-hover">
    <tbody>
        @foreach ($data as $index => $item)
            <tr>
                <td class="w-10px">{{ $item['nomor'] }}</td>
                <td>{!! $item['uraian'] !!}</td>
                <td class="text-end">{{ $item['nilai'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
