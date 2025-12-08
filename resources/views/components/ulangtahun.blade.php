<div>
    @if ($data->count() > 0)
        <div class="alert alert-info">
            <ul>
                @foreach ($data as $item)
                    <li>Selamat ulang tahun {{ $item->nama }} tanggal {{ $item->tanggal_lahir->format('d F Y') }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
