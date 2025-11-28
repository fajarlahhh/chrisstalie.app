<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Absensi;
use Carbon\Carbon;
use Livewire\Attributes\Url;

class Home extends Component
{
    #[Url]
    public $bulanShift;

    public function mount()
    {
        $this->bulanShift = $this->bulanShift ?: date('Y-m');
    }

    public function getDataJadwalShift()
    {
        $detail = [];
        $absensi = Absensi::where('pegawai_id', auth()->user()->pegawai_id)->where('tanggal', 'like', $this->bulanShift . '%')->get();
        if (!$this->bulanShift || !preg_match('/^\d{4}-\d{2}$/', $this->bulanShift)) {
            $this->bulanShift = date('Y-m');
        }

        $startDate = Carbon::createFromFormat('Y-m-d', $this->bulanShift . '-01');
        $daysInMonth = $startDate->daysInMonth;

        for ($i = 0; $i < $daysInMonth; $i++) {
            $tanggal = $startDate->copy()->addDays($i)->format('Y-m-d');

            $data = $absensi->firstWhere('tanggal', $tanggal);
            $detail[] = $data ? [
                'jam_masuk' => $data->jam_masuk,
                'jam_pulang' => $data->jam_pulang,
                'tanggal' => $data->tanggal,
                'shift_id' => $data->shift_id,
                'masuk' => $data->masuk,
                'pulang' => $data->pulang,
                'absen' => $data->shift_id ? true : false,
            ] : [
                'jam_masuk' => null,
                'jam_pulang' => null,
                'tanggal' => $tanggal,
                'shift_id' => null,
                'masuk' => null,
                'pulang' => null,
                'absen' => false,
            ];
        }
        return $detail;
    }
    public function render()
    {
        return view(
            'livewire.home',
            [
                'dataJadwalShift' => auth()->user()->pegawai_id ? $this->getDataJadwalShift() : [],
            ]
        );
    }
}
