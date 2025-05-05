<div class="text-center">
    <img src="/assets/img/login.png" class="w-50px">
</div>
<div class="text-center fs-11px">
    <h5><u>SLIP GAJI</u></h5>
</div>
<table class="table table-borderless fs-11px">
    <tr>
        <td class="text-nowrap w-100px p-0">Nama</td>
        <td class="p-0">: {{ $data->pegawai->nama }}</td>
    </tr>
    <tr>
        <td class="text-nowrap p-0">Deskripsi</td>
        <td class="p-0" colspan="2">: {{ $data->uraian }}</td>
    </tr>
</table>
<table class="table table-bordered fs-11px">
    <tr>
        <th>Uraian</th>
        <th>Nilai</th>
    </tr>
    @php
        $total = 0;
    @endphp
    @foreach ($data->expenditureDetail as $key => $row)
        @if (strpos($row->uraian, '-') !== false)
            <tr>
                <th>
                    Total Gaji + Tunjangan</th>
                <th class="text-end">
                    {{ number_format($total) }}</th>
            </tr>
            @php
                $total -= $row->cost;
            @endphp
        @else
            @php
                $total += $row->cost;
            @endphp
        @endif
        <tr>
            <td>
                {{ $row->uraian }}</td>
            <td class="text-end">
                {{ number_format($row->cost) }}</td>
        </tr>
    @endforeach
    <tr>
        <th>JUMLAH TERIMA</th>
        <th class="text-end">
            {{ number_format($total) }}</th>
    </tr>
</table>
