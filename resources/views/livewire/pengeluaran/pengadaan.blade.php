<div>
    @section('title', 'Pengadaan Pelunasan')

    @section('breadcrumb')
        <li class="breadcrumb-item">Pengeluaran</li>
        <li class="breadcrumb-item active">Pengadaan Pelunasan</li>
    @endsection

    <h1 class="page-header">Pengadaan</h1>
    <div class="panel panel-inverse" data-sortable-id="form-stuff-1">
        <div class="panel-heading">
            <div class="w-100">
                <div class="panel-heading-btn float-end">
                    <select class="form-control" wire:model.live="status" data-size="5">
                        <option value="1">Belum Lunas</option>
                        <option value="2">Sudah Lunas</option>
                    </select>&nbsp;
                    @if ($status == 2)
                        <select wire:model.lazy="month" class="form-control w-auto">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ sprintf('%02s', $i) }}">
                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}</option>
                            @endfor
                        </select>&nbsp;
                        <select wire:model.lazy="year" class="form-control w-auto">
                            @for ($i = 2023; $i <= date('Y'); $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>&nbsp;
                    @endif
                    <input type="text" class="form-control w-200px" placeholder="Cari"
                        aria-label="Sizing example input" autocomplete="off" aria-describedby="basic-addon2"
                        wire:model.lazy="search">
                </div>
            </div>
        </div>
        <div class="panel-body table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="w-10px">No.</th>
                        <th>Tanggal</th>
                        <th>No. Bukti</th>
                        <th>Deskripsi</th>
                        <th>Jatuh Tempo</th>
                        <th>Barang/Item</th>
                        @if ($status == 2)
                            <th>Pelunasan</th>
                        @endif
                        <th class="w-10px"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->receipt }}</td>
                            <td>{{ $row->description }}</td>
                            <td>{{ $row->due_date }}</td>
                            <td class="w-400px">
                                <table class="table-bordered fs-10px">
                                    <tr class="bg-gray-100">
                                        <th class="text-nowrap w-250px p-1">Barang/Item</th>
                                        <th class="w-100px p-1">Harga Satuan</th>
                                        <th class="w-50px p-1">Qty</th>
                                        <th class="w-100px p-1">Harga</th>
                                    </tr>
                                    @foreach ($row->purchaseDetail as $j => $subRow)
                                        <tr>
                                            <td class="p-1">
                                                {{ $subRow->goods_id ? $subRow->goods->name : $subRow->name }}</td>
                                            <td class="text-end p-1  text-nowrap">
                                                {{ number_format($subRow->price) }}</td>
                                            <td class="text-end p-1  text-nowrap">
                                                {{ number_format($subRow->qty) }}</td>
                                            <td class="text-end p-1  text-nowrap">
                                                {{ number_format($subRow->qty * $subRow->price) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="p-1" colspan="3">Total</td>
                                        <td class="text-end p-1  text-nowrap">
                                            {{ number_format($row->purchaseDetail->sum(fn($q) => $q->price * $q->qty)) }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            @if ($status == 2)
                                <td class="text-nowrap">{{ $row->expenditure->date }},
                                    {{ $row->expenditure->description }}<br>
                                    {{ $row->expenditure->user->name }}</td>
                            @endif
                            <td class="with-btn-group" nowrap>
                                @role('administrator|supervisor|operator')
                                    @if ($status == 1)
                                        <form wire:submit.prevent="submit({{ $index }}, {{ $row->getKey() }})">
                                            <input type="date" class="form-control w-200px"
                                                aria-label="Sizing example input" autocomplete="off"
                                                aria-describedby="basic-addon2"
                                                wire:model="expenditure.{{ $index }}.date" required>
                                            <input type="text" class="form-control w-200px"
                                                placeholder="Keterangan Pelunasan" aria-label="Sizing example input"
                                                autocomplete="off" aria-describedby="basic-addon2"
                                                wire:model="expenditure.{{ $index }}.description" required>
                                            <input type="submit" value="Simpan" class="btn btn-success" />
                                        </form>
                                    @else
                                        <x-action :row="$row" custom="" :detail="false" :edit="false"
                                            :print="false" :permanentDelete="false" :restore="false" :delete="true" />
                                    @endif
                                @endrole
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <x-alert />
</div>
