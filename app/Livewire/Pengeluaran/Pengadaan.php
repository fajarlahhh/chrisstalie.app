<?php

namespace App\Livewire\Pengeluaran;

use Livewire\Component;
use App\Models\Purchase;
use App\Models\Rekasir;
use App\Models\Expenditure;
use Livewire\Attributes\Url;
use App\Models\ExpenditureDetail;
use Illuminate\Support\Facades\DB;

class Pengadaan extends Component
{
    public $expenditure = [];
    #[Url]
    public $search, $month, $year, $status = 1;

    public function submit($index, $id)
    {
        DB::transaction(function () use ($index, $id) {
            $purchase = Purchase::find($id);
            
            $expenditure = new Expenditure();
            $expenditure->type = 'form';
            $expenditure->date = $this->expenditure[$index]['date'];
            $expenditure->uraian = $this->expenditure[$index]['uraian'];
            $expenditure->purchase_id = $id;
            $expenditure->pengguna_id = auth()->id();
            $expenditure->save();

            ExpenditureDetail::insert(collect($purchase->purchaseDetail)->map(fn($q) => [
                'expenditure_id' => $expenditure->id,
                'cost' => $q['harga'],
                'uraian' => $q->goods->nama
            ])->toArray());
        });

        session()->flash('success', 'Berhasil menyimpan data');
    }

    public function delete($id)
    {
        Expenditure::where('purchase_id', $id)->delete();
        session()->flash('success', 'Berhasil menghapus data');
    }

    public function mount()
    {
        $this->month = date('m');
        $this->year = date('Y');
    }

    public function render()
    {
        return view('livewire.pengeluaran.pengadaan', [
            'data' => Purchase::with(['purchaseDetail.goods', 'pengguna', 'stokMasuk', 'expenditure.pengguna'])->where(fn($q) => $q->where('receipt', 'like', '%' . $this->search . '%')->orWhere('uraian', 'like', '%' . $this->search . '%'))
                ->when($this->status == 1, fn($q) => $q->whereDoesntHave('expenditure'))
                ->when($this->status == 2, fn($q) => $q->whereHas('expenditure', fn($r) => $r->where('date', 'like', $this->year . '-' . $this->month . '%')))->whereNotNull('due_date')->orderBy('created_at', 'desc')->get(),
        ]);
    }
}
