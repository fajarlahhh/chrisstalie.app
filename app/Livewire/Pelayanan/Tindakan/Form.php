<?php

namespace App\Livewire\Pelayanan\Tindakan;

use App\Models\ActionRate;
use App\Models\Goods;
use App\Models\Practitioner;
use Livewire\Component;
use App\Models\Treatment;
use App\Models\Registration;
use App\Models\ToolMaterial;
use Illuminate\Support\Facades\DB;

class Form extends Component
{
    public $date, $data, $dataActionRate = [], $dataPractitioner = [], $treatment = [], $toolsAndMaterial = [], $dataGoods = [];

    public function changeActionRate($index)
    {
        $this->treatment[$index]['practitioner_id'] = [];
        $this->treatment[$index]['beautician_id'] = [];
    }

    public function mount(Registration $data)
    {
        $this->date = $this->date ?: date('Y-m-d');
        $this->data = $data;
        $this->dataPractitioner = Practitioner::with('employee')->orderBy('nama')->get()->toArray();
        $this->dataActionRate = ActionRate::orderBy('nama')->get()->toArray();
        $this->dataGoods = Goods::orderBy('nama')->whereNull('consignment_id')->get()->toArray();
        $this->treatment = $data->treatment->map(fn($q) => [
            'qty' => $q->qty,
            'action_rate_id' => $q->action_rate_id,
            'practitioner_id' => $q->practitioner_id,
            'beautician_id' => $q->beautician_id
        ])->toArray();
        $this->toolsAndMaterial = $data->toolMaterial->map(fn($q) => [
            'goods_id' => $q->goods_id,
            'qty' => $q->qty
        ])->toArray();
    }

    public function addTreatment()
    {
        $this->treatment[] = [
            'action_rate_id' => null,
            'practitioner_id' => null,
            'beautician_id' => null
        ];
    }

    public function deleteTreatment($index)
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
        unset($this->treatment[$index]);
        $this->treatment = array_merge($this->treatment);
    }

    public function submit()
    {
        $this->validate([
            'date' => 'required',
            'treatment' => 'required',
            'treatment.*.action_rate_id' => 'required',
        ]);

        DB::transaction(function () {
            Treatment::where('registration_id', $this->data->id)->delete();
            ToolMaterial::where('registration_id', $this->data->id)->delete();
            Treatment::insert(collect($this->treatment)->map(function ($q, $key) {
                return [
                    'registration_id' => $this->data->id,
                    'action_rate_id' => $q['action_rate_id'],
                    'practitioner_id' => $q['practitioner_id'] ?: null,
                    'beautician_id' => $q['beautician_id'] ?: null,
                    'qty' => $q['qty'],
                    'date' => $this->date,
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            })->toArray());
            ToolMaterial::insert(collect($this->toolsAndMaterial)->where('goods_id', '!=', null)->map(function ($q, $key) {
                return [
                    'registration_id' => $this->data->id,
                    'goods_id' => $q['goods_id'],
                    'qty' => $q['qty'],
                    'date' => $this->date,
                    'user_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            })->toArray());

            session()->flash('success', 'Berhasil menyimpan data');
        });
        $this->redirect('/pelayanan/tindakan');
    }

    public function render()
    {
        return view('livewire.pelayanan.tindakan.form');
    }
}
