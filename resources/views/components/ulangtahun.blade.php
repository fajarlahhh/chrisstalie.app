<div>
    @if ($data->count() > 0)
        <div class="alert alert-info">
            <div class="p-2 h-100px overflow-auto fs-11px">
                Terdapat {{ $data->count() }} pasien yang ulang tahun dalam 5 hari ke depan dan 5 sebelumnya:
                <ol>
                    @foreach ($data as $item)
                        <li>{{ $item->nama }} ({{ $item->id }}) <strong>yang ke
                                {{ \Carbon\Carbon::parse($item->tanggal_lahir)->age }}</strong>
                            (<strong>{{ $item->tanggal_lahir->format('d F') }}</strong>)
                        </li>
                    @endforeach
                </ol>
            </div>
        </div>
    @endif
</div>
