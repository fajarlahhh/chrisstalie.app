<?php

namespace App\Livewire\Rekapitulasistok;

use App\Models\Goods;
use App\Models\GoodsBalance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $year, $month;

    public function mount()
    {
        $this->year = $this->year ?: date('Y');
        $this->month = $this->month ?: date('m');
    }

    public function submit()
    {
        DB::transaction(function () {
            $nextPeriod = Carbon::parse($this->year . '-' . $this->month . '-01')->addMonths(1)->format('Y-m-01');
            $data = Goods::with(['goodsBalance' => fn($q) => $q->where('period', 'like',  $this->year . '-' . $this->month . '%')])
                ->with(['incomingStock' => fn($q) => $q->where('date', 'like',  $this->year . '-' . $this->month . '%')])
                ->with(['saleDetail' => fn($q) => $q->whereHas('sale', fn($r) => $r->where('date', 'like',  $this->year . '-' . $this->month . '%'))])
                ->get();
            GoodsBalance::where('period', $nextPeriod)->delete();
            GoodsBalance::insert($data->map(
                fn($q) =>
                [
                    'goods_id' => $q->id,
                    'period' =>  $nextPeriod,
                    'qty' => $q->goodsBalance->sum('qty') + $q->incomingStock->sum('qty') - $q->saleDetail->sum('qty'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            )->toArray());
            session()->flash('success', 'Berhasil menyimpan data');
        });
    }

    public function render()
    {
        return view('livewire.rekapitulasistok.index');
    }
}
