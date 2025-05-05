<?php

namespace App\Livewire\Pengeluaran;

use Livewire\Component;
use App\Models\Pegawai;
use App\Models\Expenditure;
use App\Models\ExpenditureDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Gaji extends Component
{
    public $data, $previous, $detail = [], $other = ['Uang Makan', 'Jasa Pelayanan', 'Bonus'], $otherCost = [];
    public $date, $uraian, $cost, $receipt;

    public function updatedDate()
    {
        $this->uraian = "Gaji bulan " . substr($this->date, 0, 7);
    }

    public function mount(Expenditure $data)
    {
        $this->previous = url()->previous();
        $this->date = $this->date ?: date('Y-m-d');
        $this->data = $data;
        $this->updatedDate();
        $this->detail = Pegawai::all()->map(fn($q) => [
            'id' => $q->id,
            'nik' => $q->nik,
            'nama' => $q->nama,
            'gaji' => $q->gaji,
            'tunjangan' => $q->tunjangan,
            'tunjangan_transport' => $q->tunjangan_transport,
            'tunjangan_bpjs' => $q->tunjangan_bpjs
        ]);
        foreach ($this->detail as $key => $row) {
            $this->otherCost[] = [
                'id' => $row['id'],
                'nik' => $row['nik'],
                'nama' => $row['nama'],
                'uang_makan' => 0,
                'jasa_pelayanan' => 0,
                'bonus' => 0
            ];
        }
    }

    public function submit()
    {
        $this->validate([
            'date' => 'required',
        ]);

        DB::transaction(function () {
            $expenditure = [];
            $now = Carbon::now();
            foreach ($this->detail as $key => $row) {
                $expenditure[] = [
                    'date' => $this->date,
                    'pengguna_id' => auth()->id(),
                    'type' => 'gaji',
                    'uraian' => 'Gaji ' . $row['nama'],
                    'pegawai_id' => $row['id'],
                    'cost' => $row['gaji'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $expenditure[] = [
                    'date' => $this->date,
                    'pengguna_id' => auth()->id(),
                    'type' => 'gaji',
                    'uraian' => 'Tunjangan ' . $row['nama'],
                    'pegawai_id' => $row['id'],
                    'cost' => $row['tunjangan'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $expenditure[] = [
                    'date' => $this->date,
                    'pengguna_id' => auth()->id(),
                    'type' => 'gaji',
                    'uraian' => 'Tunjangan Transport ' . $row['nama'],
                    'pegawai_id' => $row['id'],
                    'cost' => $row['tunjangan'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $expenditure[] = [
                    'date' => $this->date,
                    'pengguna_id' => auth()->id(),
                    'type' => 'gaji',
                    'uraian' => 'BPJS Kesehatan ' . $row['nama'],
                    'pegawai_id' => $row['id'],
                    'cost' => $row['tunjangan_bpjs'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            foreach ($this->otherCost as $key => $row) {
                $expenditure[] = [
                    'date' => $this->date,
                    'pengguna_id' => auth()->id(),
                    'type' => 'gaji',
                    'uraian' => 'Uang Makan ' . $row['nama'],
                    'pegawai_id' => $row['id'],
                    'cost' => $row['uang_makan'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $expenditure[] = [
                    'date' => $this->date,
                    'pengguna_id' => auth()->id(),
                    'type' => 'gaji',
                    'uraian' => 'Jasa Pelayanan ' . $row['nama'],
                    'pegawai_id' => $row['id'],
                    'cost' => $row['jasa_pelayanan'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
                $expenditure[] = [
                    'date' => $this->date,
                    'pengguna_id' => auth()->id(),
                    'type' => 'gaji',
                    'uraian' => 'Bonus ' . $row['nama'],
                    'pegawai_id' => $row['id'],
                    'cost' => $row['bonus'],
                    'created_at' => $now,
                    'updated_at' => $now
                ];
            }
            Expenditure::insert($expenditure);

            session()->flash('success', 'Berhasil menyimpan data');
        });

        $this->redirect($this->previous);
    }

    public function render()
    {
        return view('livewire.pengeluaran.gaji');
    }
}
