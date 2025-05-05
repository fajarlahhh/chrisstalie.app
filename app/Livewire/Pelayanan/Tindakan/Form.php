<?php

namespace App\Livewire\Pelayanan\PelayananTindakan;

use App\Models\Tarif;
use App\Models\Barang;
use App\Models\Nakes;
use Livewire\Component;
use App\Models\PelayananTindakan;
use App\Models\Pendaftaran;
use App\Models\ToolMaterial;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $date, $data, $dataTarif = [], $dataNakes = [], $pelayananTindakan = [], $toolsAndMaterial = [], $dataGoods = [];

    public function changeTarif($index)
    {
        $this->pelayananTindakan[$index]['nakes_id'] = [];
        $this->pelayananTindakan[$index]['beautician_id'] = [];
    }

    public function mount(Pendaftaran $data)
    {
        $this->date = $this->date ?: date('Y-m-d');
        $this->data = $data;
        $this->dataNakes = Nakes::with('pegawai')->orderBy('nama')->get()->toArray();
        $this->dataTarif = Tarif::orderBy('nama')->get()->toArray();
        $this->dataGoods = Barang::orderBy('nama')->whereNull('consignment_id')->get()->toArray();
        $this->pelayananTindakan = $data->pelayananTindakan->map(fn($q) => [
            'qty' => $q->qty,
            'action_rate_id' => $q->action_rate_id,
            'nakes_id' => $q->nakes_id,
            'beautician_id' => $q->beautician_id
        ])->toArray();
        $this->toolsAndMaterial = $data->toolMaterial->map(fn($q) => [
            'goods_id' => $q->goods_id,
            'qty' => $q->qty
        ])->toArray();
    }

    public function addPelayananTindakan()
    {
        $this->pelayananTindakan[] = [
            'action_rate_id' => null,
            'nakes_id' => null,
            'beautician_id' => null
        ];
    }

    public function deletePelayananTindakan($index)
    {
        unset($this->toolsAndMaterial[$index]);
        $this->toolsAndMaterial = array_merge($this->toolsAndMaterial);
    }

    public function addToolsAndMaterials()
    {
        $this->toolsAndMaterial[] = [
            'goods_id' => null,
            'qty' => null
        ];
    }

    public function deleteToolsAndMaterials($index)
    {
        unset($this->pelayananTindakan[$index]);
        $this->pelayananTindakan = array_merge($this->pelayananTindakan);
    }

    public function submit()
    {
        $this->validate([
            'date' => 'required',
            'pelayananTindakan' => 'required',
            'pelayananTindakan.*.action_rate_id' => 'required',
        ]);

        DB::transaction(function () {
            PelayananTindakan::where('pendaftaran_id', $this->data->id)->delete();
            ToolMaterial::where('pendaftaran_id', $this->data->id)->delete();
            PelayananTindakan::insert(collect($this->pelayananTindakan)->map(function ($q, $key) {
                return [
                    'pendaftaran_id' => $this->data->id,
                    'action_rate_id' => $q['action_rate_id'],
                    'nakes_id' => $q['nakes_id'] ?: null,
                    'beautician_id' => $q['beautician_id'] ?: null,
                    'qty' => $q['qty'],
                    'date' => $this->date,
                    'pengguna_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            })->toArray());
            ToolMaterial::insert(collect($this->toolsAndMaterial)->where('goods_id', '!=', null)->map(function ($q, $key) {
                return [
                    'pendaftaran_id' => $this->data->id,
                    'goods_id' => $q['goods_id'],
                    'qty' => $q['qty'],
                    'date' => $this->date,
                    'pengguna_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            })->toArray());

            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pelayanan/pelayananTindakan');
    }

    public function render()
    {
        return view('livewire.pelayanan.pelayananTindakan.form');
    }
}
