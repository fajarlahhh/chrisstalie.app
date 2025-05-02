<?php

use App\Models\Icd10;
use App\Models\Loinc;
use App\Models\Icd9cm;
use App\Models\Patient;
use App\Models\SnomedCt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within the "web" middleware group.
|
*/

if (!function_exists('routeName')) {
    function routeName($row, $url)
    {
        if (!empty($row['method'])) {
            foreach ($row['method'] as $method) {
                if (class_exists("\\App\\Livewire\\" . ucfirst($url) . "\\" . $method)) {
                    Route::get(
                        '/' . ($method == "Index" ? "" : strtolower($method) . "/{data?}"),
                        "\\App\\Livewire\\" . $url  . "\\" .  ucfirst($method)
                    )
                        ->middleware(['role_or_permission:administrator|' . str_replace('\\', '', strtolower($url))])
                        ->name(str_replace('\\', '.', strtolower($url)) . '.' . strtolower($method));
                }
            }
        }
    }
}

if (!function_exists('subRoutes')) {
    function subRoutes($subRoutes, $parentUrl)
    {
        foreach ($subRoutes as $row) {
            $url = str_replace([' ', '/', '&', '\'', ',', '(', ')', '.'], '', strtolower($row['title']));
            Route::prefix($url)->group(function () use ($parentUrl, $url, $row) {
                if (empty($row['sub_menu'])) {
                    routeName($row, ucfirst($parentUrl) . "\\" . ucfirst($url));
                } else {
                    subRoutes($row['sub_menu'], ucfirst($parentUrl) . "\\" . ucfirst($url));
                }
            });
        }
    }
}

Route::middleware(['auth'])->group(function () {
    Route::post('logout', function () {
        auth()->logout();
        return  redirect('login');
    });
    Route::redirect('/', '/home');
    Route::get('/home', \App\Livewire\Home::class)->name('home');
    Route::get('/gantipassword', \App\Livewire\Gantipassword::class);

    Route::prefix('search')->group(function () {
        Route::get('patient', function (Request $req) {
            return Patient::where(fn($q) => $q->where('nik', 'like', "%$req->search%")->orWhere('address', 'like', "%$req->search%")->orWhere('name', 'like', "%$req->search%"))->orderBy('name', 'asc')->get()
                ->map(fn($q) => [
                    'id' => $q->id,
                    'text' => $q->name . ' ' . $q->address,
                    'rm' => $q->rm,
                    'nik' => $q->nik ?: '',
                    'name' => $q->name,
                    'address' => $q->address,
                ])->toArray();
        });

        Route::get('/icd10', function (Request $req) {
            return Icd10::where('description', 'like', '%' . $req->search . '%')->orWhere('code', 'like', '%' . $req->search . '%')->take(100)->get()->map(fn($q) => [
                'id' => $q->code,
                'text' => $q->code . " - " . $q->description,
                'description' => $q->description
            ]);
        });

        Route::get('/icd9cm', function (Request $req) {
            return Icd9cm::where('description', 'like', '%' . $req->search . '%')->orWhere('code', 'like', '%' . $req->search . '%')->take(100)->get()->map(fn($q) => [
                'id' => $q->code,
                'text' => $q->code . " - " . $q->description,
                'description' => $q->description
            ]);
        });

        Route::get('/snomedct', function (Request $req) {
            return SnomedCt::where(fn($q) => $q->where('term', 'like', '%' . $req->search . '%')->orWhere('concept_id', 'like', '%' . $req->search . '%'))->where('active', 1)->groupBy('concept_id', 'term')->select('concept_id', 'term')->take(100)->get()->map(fn($q) => [
                'id' => $q->concept_id,
                'text' => $q->concept_id . ' - ' . $q->term,
                'description' => $q->term,
            ])->all();
        });

        Route::get('/loinc', function (Request $req) {
            return Loinc::where('long_common_name', 'like', '%' . $req->search . '%')->orWhere('number', 'like', '%' . $req->search . '%')->take(100)->get()->map(fn($q) => [
                'id' => $q->number,
                'text' => $q->number . " - " . $q->long_common_name . ($q->unit ? "(" . $q->unit . ")" : null),
                'description' => $q->long_common_name,
                'unit' => $q->unit ? $q->unit : null,
            ]);
        });
    });

    foreach (collect(config('sidebar.menu'))->sortBy('title')->toArray() as $row) {
        $url = str_replace([' ', '/', '&', '\'', ',', '(', ')', '.'], '', strtolower($row['title']));
        Route::prefix($url)->group(function () use ($url, $row) {
            if (empty($row['sub_menu'])) {
                routeName($row, $url);
            } else {
                subRoutes($row['sub_menu'], $url);
            }
        });
    }
});
