<?php

namespace App\Livewire\Pengadaanbrgdagang\Pelunasan;

use Livewire\Component;
use App\Models\PelunasanPembelian;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

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
        PelunasanPembelian::findOrFail($id)->forceDelete();
        session()->flash('success', 'Berhasil menghapus data');
    }

    public function updated()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view(
            'livewire.pengadaanbrgdagang.pelunasan.index',
            [
                'data' => PelunasanPembelian::with(['pembelian', 'jurnal', 'pengguna.pegawai', 'kodeAkunPembayaran'])
                ->orderBy('created_at', 'desc')->paginate(10)
            ]
        );
    }
}
