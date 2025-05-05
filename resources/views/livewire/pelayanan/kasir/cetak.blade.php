<div class="text-center">
    <img src="/assets/img/login.png" class="w-200px">
</div>
<br>
<br>
<table class="table table-borderless fs-11px">
    <tr>
        <td class="text-nowrap w-50px p-0">Kasir</td>
        <td class="p-0">: {{ $data->pengguna->pegawai ? $data->pengguna->pegawai->nama : $data->pengguna->nama }}</td>
    </tr>
    <tr>
        <td class="text-nowrap p-0">Tanggal</td>
        <td class="p-0" colspan="2">: {{ $data->created_at }}</td>
    </tr>
</table>
<hr>
<table class="table table-borderless fs-11px">
    @foreach ($data->kasirPelayananTindakan as $pelayananTindakan)
        <tr>
            <td class="p-0">{{ $pelayananTindakan->tarif->nama }}</td>
        </tr>
        <tr>
            <td class="p-0 ps-2">{{ number_format($pelayananTindakan->qty) }} x
                {{ number_format($pelayananTindakan->harga - ($pelayananTindakan->harga * $pelayananTindakan->discount) / 100) }}
            </td>
            <td class="p-0 text-end">Rp.
                {{ number_format($pelayananTindakan->qty * ($pelayananTindakan->harga - ($pelayananTindakan->harga * $pelayananTindakan->discount) / 100)) }}
            </td>
        </tr>
    @endforeach
    @if ($data->sale)
        @foreach ($data->sale->saleDetail as $toolsMaterial)
            <tr>
                <td class="p-0">{{ $toolsMaterial->goods->nama }}</td>
            </tr>
            <tr>
                <td class="p-0 ps-2">{{ number_format($toolsMaterial->qty) }} x
                    {{ number_format($toolsMaterial->harga - ($toolsMaterial->harga * $toolsMaterial->discount) / 100) }}
                </td>
                <td class="p-0 text-end">Rp.
                    {{ number_format($toolsMaterial->qty * ($toolsMaterial->harga - ($toolsMaterial->harga * $toolsMaterial->discount) / 100)) }}
                </td>
            </tr>
        @endforeach
    @endif
</table>
<hr>
<table class="table table-borderless fs-11px">
    <tr>
        <td class="p-0">Sub Total</td>
        <td class="p-0 text-end">Rp. {{ number_format($data->amount - $data->admin) }}</td>
    </tr>
    <tr>
        <td class="p-0">By. Admin</td>
        <td class="p-0 text-end">Rp. {{ number_format($data->admin) }}</td>
    </tr>
    <tr>
        <td class="p-0">Total</td>
        <td class="p-0 text-end">Rp. {{ number_format($data->amount) }}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="p-0">Bayar</td>
        <td class="p-0 text-end">Rp. {{ number_format($data->cash) }}</td>
    </tr>
    <tr>
        <td class="p-0">Kembali</td>
        <td class="p-0 text-end">Rp. {{ number_format($data->cash - $data->amount) }}</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="text-center">
            <h3>TERIMA KASIH</h3>
        </td>
    </tr>
</table>
