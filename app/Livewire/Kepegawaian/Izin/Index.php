<?php

namespace App\Livewire\Kepegawaian\Izin;

use App\Models\KepegawaianAbsensi;
use Livewire\Component;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $tanggal1, $tanggal2;
    public function mount()
    {
        $this->tanggal1 = $this->tanggal1 ?: date('Y-m-01');
        $this->tanggal2 = $this->tanggal2 ?: date('Y-m-d');
    }

    public function updatedCari()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        DB::transaction(function () use ($id) {
            $data = KepegawaianAbsensi::findOrFail($id);
            if($data->masuk != null || $data->pulang != null){
                $data->delete();
            } else {
                $data->update([
                    'izin' => null,
                    'keterangan' => null,
                ]);
            }
        });
    }

    public function render()
    {
        return view('livewire.kepegawaian.izin.index', [
            'data' => KepegawaianAbsensi::with('kepegawaianPegawai')->with('jadwalShiftPegawaiDetail')
                ->whereNotNull('izin')
                ->whereBetween('tanggal', [$this->tanggal1, $this->tanggal2])
                ->when($this->cari, fn($q) => $q
                    ->whereHas('kepegawaianPegawai', fn($r) => $r
                        ->where('nama', 'ilike', '%' . $this->cari . '%')))
                ->orderBy('tanggal', 'desc')
                ->paginate(10)
        ]);
    }
}
