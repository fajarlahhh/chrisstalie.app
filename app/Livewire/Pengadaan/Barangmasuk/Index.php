<?php

namespace App\Livewire\Pengadaan\Barangmasuk;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\StokMasuk;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $bulan;


    public function mount()
    {
        $this->bulan = $this->bulan ?: date('Y-m');
    }

    public function delete($id)
    {
        $data = StokMasuk::find($id);
        if ($data->stok->keluar == 0) {
            $data->delete();
        }
    }

    public function render()
    {
        return view('livewire.pengadaan.barangmasuk.index', [
            'data' => StokMasuk::with(['pengguna', 'barang', 'pembelian'])
                ->where('created_at', 'like', $this->bulan . '%')
                ->whereHas('barang', fn($q) => $q->where('nama', 'like', '%' . $this->cari . '%'))
                ->whereHas('pembelian', fn($q) => $q->where('uraian', 'like', '%' . $this->cari . '%'))
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }
}
