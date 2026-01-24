<?php

namespace App\Livewire\Kepegawaian\AbsensiPegawai;

use App\Models\AbsensiPegawai;
use Livewire\Component;
use App\Models\KehadiranPegawai;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Models\Pegawai;

class Index extends Component
{
    use WithPagination;

    #[Url]
    public $cari, $tanggal1, $tanggal2, $pegawai_id;
    public $dataPegawai = [];

    public function mount()
    {
        $this->tanggal1 = $this->tanggal1 ?: date('Y-m-01');
        $this->tanggal2 = $this->tanggal2 ?: date('Y-m-d');
        $this->dataPegawai = Pegawai::orderBy('nama')->get()->toArray();
    }

    public function updatedCari()
    {
        $this->resetPage();
    }

    public function hapus($id)
    {
        AbsensiPegawai::findOrFail($id)->delete();
    }

    private function parse($data, $p1, $p2)
    {
        $data = " " . $data;
        $hasil = "";
        $awal = strpos($data, $p1);
        if ($awal != "") {
            $akhir = strpos(strstr($data, $p1), $p2);
            if ($akhir != "") {
                $hasil = substr($data, $awal + strlen($p1), $akhir - strlen($p1));
            }
        }
        return $hasil;
    }

    public function posting()
    {
        DB::transaction(function () {
            $dataAbsensiPegawai = AbsensiPegawai::with(['pegawai.kehadiranPegawai'])
                ->whereBetween('tanggal', [$this->tanggal1, $this->tanggal2])
                ->when(
                    $this->cari,
                    fn($q) => $q->whereHas('pegawai', fn($r) => $r
                        ->where('nama', 'ilike', '%' . $this->cari . '%'))
                )
                ->orderBy('tanggal')->get()->map(function ($q) {
                    $kehadiranPegawai = $q->pegawai->kehadiranPegawai->where('tanggal', $q->tanggal);
                    $masuk = $kehadiranPegawai->first()?->waktu;
                    $pulang = $kehadiranPegawai->last()?->waktu;
                    return [
                        'id' => $q->id,
                        'masuk' => $masuk,
                        'pulang' => $pulang,
                    ];
                });
            foreach ($dataAbsensiPegawai as $absensi) {
                AbsensiPegawai::where('id', $absensi['id'])->update([
                    'masuk' => $absensi['masuk'],
                    'pulang' => $absensi['pulang'],
                ]);
            }

            session()->flash('success', 'Berhasil mengambil data absensi');
        });
    }

    public function download()
    {
        ini_set('max_execution_time', 300);
        $Connect = fsockopen(config('app.fingerprint_ip'), "80", $errno, $errstr, 30);
        if ($Connect) {

            $soap_request = "<GetAttLog><ArgComKey xsi:type=\"xsd:integer\">" . config('app.fingerprint_key') . "</ArgComKey><Arg><PIN xsi:type=\"xsd:integer\">All</PIN></Arg></GetAttLog>";
            $newLine = "\r\n";
            fputs($Connect, "POST /iWsService HTTP/1.0" . $newLine);
            fputs($Connect, "Content-Type: text/xml" . $newLine);
            fputs($Connect, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
            fputs($Connect, $soap_request . $newLine);
            $buffer = "";
            while ($Response = fgets($Connect, 1024)) {
                $buffer = $buffer . $Response;
            }
        }

        $buffer = $this->parse($buffer, "<GetAttLogResponse>", "</GetAttLogResponse>");
        $buffer = explode("\r\n", $buffer);
        $dataKehadiran = [];
        for ($i = 0; $i < count($buffer); $i++) {
            $data = $this->parse($buffer[$i], "<Row>", "</Row>");;
            if ($data) {
                array_push($dataKehadiran, [
                    'id' => $this->parse($data, "<DateTime>", "</DateTime>") . '-' . (int)$this->parse($data, "<PIN>", "</PIN>"),
                    'pegawai_id' => (int)$this->parse($data, "<PIN>", "</PIN>"),
                    'waktu' =>  substr($this->parse($data, "<DateTime>", "</DateTime>"), 11, 8),
                    'tanggal' =>  substr($this->parse($data, "<DateTime>", "</DateTime>"), 0, 10),
                    'kode' => $this->parse($data, "<Status>", "</Status>"),
                    'masuk' => $this->parse($data, "<Status>", "</Status>") == '0' ? $this->parse($data, "<DateTime>", "</DateTime>") : null,
                    'pulang' => $this->parse($data, "<Status>", "</Status>") == '1' ? $this->parse($data, "<DateTime>", "</DateTime>") : null,
                ]);
            }
        }
        DB::transaction(function () use ($dataKehadiran) {
            foreach ($dataKehadiran as $kehadiranPegawai) {
                KehadiranPegawai::insertOrIgnore($kehadiranPegawai);
            }
        });

        // $Connect1 = fsockopen(config('app.fingerprint_ip'), "80", $errno, $errstr, 30);
        // if ($Connect1) {
        //     $soap_request = "<ClearData><ArgComKey xsi:type=\"xsd:integer\">" . config('app.fingerprint_key') . "</ArgComKey><Arg><Value xsi:type=\"xsd:integer\">3</Value></Arg></ClearData>";
        //     $newLine = "\r\n";
        //     fputs($Connect1, "POST /iWsService HTTP/1.0" . $newLine);
        //     fputs($Connect1, "Content-Type: text/xml" . $newLine);
        //     fputs($Connect1, "Content-Length: " . strlen($soap_request) . $newLine . $newLine);
        //     fputs($Connect1, $soap_request . $newLine);
        //     $buffer1 = "";
        //     while ($Response1 = fgets($Connect1, 1024)) {
        //         $buffer1 = $buffer1 . $Response1;
        //     }
        // }
        session()->flash('success', 'Berhasil mengambil data absensi');
    }

    public function render()
    {
        return view('livewire.kepegawaian.absensi.index', [
            'data' => AbsensiPegawai::with(['pegawai.kehadiranPegawai'])
                ->when($this->pegawai_id, fn($q) => $q->where('pegawai_id', $this->pegawai_id))
                ->whereBetween('tanggal', [$this->tanggal1, $this->tanggal2])
                ->when(
                    $this->cari,
                    fn($q) => $q->whereHas('pegawai', fn($r) => $r
                        ->where('nama', 'ilike', '%' . $this->cari . '%'))
                )
                ->orderBy('tanggal')->paginate(10)
        ]);
    }
}
