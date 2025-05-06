<?php

namespace App\Livewire\Pelayanan\Pendaftaran;

use Livewire\Component;
use App\Models\Pendaftaran;
use Livewire\Attributes\Url;

class Index extends Component
{
    #[Url]
    public $cari, $tanggal;

    public function mount()
    {
        $this->tanggal = $this->tanggal ?: date('Y-m-d');
    }

    public function delete($id)
    {
        Pendaftaran::where('id', $id)->whereDoesntHave('kasir')->delete();
    }

    public function render()
    {
        return view('livewire.pelayanan.pendaftaran.index', [
            'data' => Pendaftaran::with(['pelayananPemeriksaanAwal', 'pelayananDiagnosa', 'pelayananTindakan', 'kasir'])->with('pasien')->with('nakes')->with('pengguna')->whereHas('pasien', fn($q) => $q->where('nama', 'like', '%' . $this->cari . '%'))
                ->where('tanggal', 'like', $this->tanggal . '%')
                ->orderBy('created_at', 'desc')->paginate(10)
        ]);
    }
}
