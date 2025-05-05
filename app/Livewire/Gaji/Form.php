<?php

namespace App\Livewire\Gaji;

use Livewire\Component;
use App\Models\Pegawai;
use App\Models\Expenditure;
use App\Models\ExpenditureDetail;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $data, $previous, $pegawaiData = [], $detail = ['Uang Makan' => 0, 'Jasa Pelayanan' => 0, 'Bonus' => 0], $otherCost = [], $pegawai;
    public $date, $uraian, $cost, $receipt, $month, $year, $pegawai_id;

    public function updatedDate()
    {
        $this->uraian = "Gaji bulan " . substr($this->date, 0, 7);
    }

    public function mount(Expenditure $data)
    {
        $this->previous = url()->previous();
        $this->month = $this->month ?: date('m');
        $this->year = $this->year ?: date('Y');
        $this->data = $data;
        $this->fill($this->data->toArray());
        $this->pegawaiData = Pegawai::orderBy('nama')->get()->toArray();
        if ($this->data->exists) {
            $this->detail = $this->data->expenditureDetail->map(fn($q) => [
                'jenis' => $q['uraian'],
                'cost' =>  $q['cost']
            ]);
            $this->pegawai = collect($this->pegawaiData)->where('id', $this->data->pegawai_id)->first();
        }
    }

    public function updatedPegawaiId()
    {
        $this->pegawai = collect($this->pegawaiData)->where('id', $this->pegawai_id)->first();
        $this->detail = [
            [
                'jenis' => ' Gaji',
                'cost' => $this->pegawai['gaji']
            ],
            [
                'jenis' => '+ Tunjangan',
                'cost' => $this->pegawai['tunjangan']
            ],
            [
                'jenis' => '+ Transport',
                'cost' => $this->pegawai['tunjangan_transport']
            ],
            [
                'jenis' => '+ Uang Makan',
                'cost' => 0
            ],
            [
                'jenis' => '+ Jasa Pelayanan',
                'cost' => 0
            ],
            [
                'jenis' => '+ Bonus',
                'cost' => 0
            ],
            [
                'jenis' => '- BPJS',
                'cost' => $this->pegawai['tunjangan_bpjs']
            ],
        ];
    }

    public function submit()
    {
        $this->validate([
            'date' => 'required',
            'month' => 'required',
            'year' => 'required',
            'pegawai_id' => 'required',
        ]);

        DB::transaction(function () {
            $total = 0;
            foreach ($this->detail as $key => $row) {
                if (strpos($row['jenis'], '-') !== false) {
                    $total -= (int)$row['cost'];
                } else {
                    $total += (int)$row['cost'];
                }
            }

            $this->data->type = 'gaji';
            $this->data->date = $this->date;
            $this->data->cost = $total;
            $this->data->pegawai_id = $this->pegawai_id;
            $this->data->uraian = "Gaji " . $this->pegawai['nama'] . ' bulan ' . $this->month . '-' . $this->year;
            $this->data->pengguna_id = auth()->id();
            $this->data->save();

            ExpenditureDetail::where('expenditure_id', $this->data->id)->delete();
            ExpenditureDetail::insert(collect($this->detail)->map(fn($q, $index) => [
                'uraian' => $q['jenis'],
                'cost' => $q['cost'],
                'expenditure_id' => $this->data->id
            ])->toArray());

            session()->flash('success', 'Berhasil menyimpan data');
        });

        $this->redirect($this->previous);
        return redirect()->to($this->previous);
    }

    public function render()
    {
        return view('livewire.gaji.form');
    }
}
