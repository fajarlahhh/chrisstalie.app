<div class="text-center">
    <img src="/assets/img/login.png" class="w-200px">
</div>
<br>
<br>
<table class="table table-borderless fs-11px">
    <tr>
        <td class="text-nowrap w-50px p-0">Kasir</td>
        <td class="p-0">: {{ $data->user->employee ? $data->user->employee->name : $data->user->name }}</td>
    </tr>
    <tr>
        <td class="text-nowrap p-0">Tanggal</td>
        <td class="p-0" colspan="2">: {{ $data->created_at }}</td>
    </tr>
</table>
<hr>
<table class="table table-borderless fs-11px">
    @foreach ($data->saleDetail as $detail)
        <tr>
            <td class="p-0">{{ $detail->goods->name }}</td>
        </tr>
        <tr>
            <td class="p-0 ps-2">{{ $detail->qty }} x
                {{ number_format($detail->price - ($detail->price * $detail->discount) / 100) }}
            </td>
            <td class="p-0 text-end">Rp.
                {{ number_format($detail->qty * ($detail->price - ($detail->price * $detail->discount) / 100)) }}
            </td>
        </tr>
    @endforeach
</table>
<hr>
<table class="table table-borderless fs-11px">
    <tr>
        <td class="p-0">Sub Total</td>
        <td class="p-0 text-end">Rp. {{ number_format($data->amount - $data->powerFee - $data->receiptFee) }}</td>
    </tr>
    <tr>
        <td class="p-0">By. Admin</td>
        <td class="p-0 text-end">Rp. {{ number_format($data->receiptFee + $data->powerFee) }}</td>
    </tr>
    <tr>
        <td class="p-0">Total</td>
        <td class="p-0 text-end">Rp.
            {{ number_format($data->amount) }}
        </td>
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
<br>
<br>