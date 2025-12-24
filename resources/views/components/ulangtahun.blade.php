<div>
    @if ($data->count() > 0)
        <div class="alert alert-info">
            <ul>
                @foreach ($data as $item)
                    <li>Selamat ulang tahun {{ $item->nama }} ({{ $item->id }}) <strong>yang ke {{ \Carbon\Carbon::parse($item->tanggal_lahir)->age }}</strong> (<strong>{{ $item->tanggal_lahir->format('d F') }}</strong>)</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
