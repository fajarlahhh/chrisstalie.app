<?php

namespace App\Livewire\Pelayanan\Kasir;

use App\Models\Payment;
use Livewire\Component;
use App\Models\Registration;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    #[Url]
    public $search, $date, $status = '1';

    public function mount()
    {
        $this->date = $this->date ?: date('Y-m-d');
    }

    public function delete($id)
    {
        Payment::where('registration_id', $id)->delete();
    }

    public function print($id)
    {
        $data = Payment::where('registration_id', $id)->first();
        $cetak = view('livewire.pelayanan.kasir.cetak', [
            'cetak' => true,
            'data' => $data,
        ])->render();
        session()->flash('cetak', $cetak);

    }

    public function render()
    {
        return view('livewire.pelayanan.kasir.index', [
            'data' => Registration::with('patient')->with('practitioner')->with('user')
                ->whereHas('patient', fn($q) => $q->where('nama', 'like', '%' . $this->search . '%'))
                ->when($this->status == '2', fn($q) => $q->whereHas('payment', fn($q) => $q->where('date', $this->date)))
                ->when($this->status == '1', fn($q) => $q->whereDoesntHave('payment'))
                ->whereHas('treatment')
                ->orderBy('created_at', 'desc')->paginate(10)
        ]);
    }
}
