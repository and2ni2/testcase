<?php

use App\Http\Controllers\CurrencyController;
use App\Imports\KatosImport;
use App\Models\Kato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('get_token', [CurrencyController::class, 'createUser']);

Route::get('excel-test', function () {
    $path1 = storage_path('kato/2019/katonew1.xls');
    $path2 = storage_path('kato/2019/katonew2.xls');
    $collection1 = Excel::toCollection(new KatosImport, $path1);
    $collection2 = Excel::toCollection(new KatosImport, $path2);
    $collection = $collection1[0]->merge($collection2[0]);

    foreach ($collection as $item) {
        if($item[0] && $item[0] !== 'te' ){
            $level = 2;
            $parent = null;
            if($item[4] !== '000' && (intval($item[4])%100 !== 0)) {
                $level = 6;
            } elseif ($item[4] !== '000' && (intval($item[4])%100 === 0)) {
                $level = 5;
            } elseif ($item[4] === '000' && $item[3] !== '00') {
                $level = 4;
            } elseif ($item[3] === '00' && $item[2] !== '00') {
                $level = 3;
            }

            Kato::create([
                'te'         => $item[0],
                'ab'         => $item[1],
                'cd'         => $item[2],
                'ef'         => $item[3],
                'hij'        => $item[4],
                'k'          => $item[5],
                'name_ru'    => $item[7],
                'name_kz'    => $item[6],
                'start_date' => \Carbon\Carbon::now()->format('Y-m-d'),
                'level'      => $level,
                'parent'     => $parent,
                'fullname_ru' => $item[7],
                'fullname_kz' => $item[6],
            ]);
        }
    }

    foreach(Kato::all() as $kato) {
        if($kato->level > 2 && $kato->level <= 6) {
            $level_5 = Kato::where('ab', $kato->ab)->where('cd', $kato->cd)->where('ef', $kato->ef)->where('hij', '=', $kato->hij[0].'00')->where('level', '=', 5)->first();
            $level_4 = Kato::where('ab', $kato->ab)->where('cd', $kato->cd)->where('ef', $kato->ef)->where('level', '=', 4)->first();
            $level_3 = Kato::where('ab', $kato->ab)->where('cd', $kato->cd)->where('level', '=', 3)->first();
            $level_2 = Kato::where('ab', $kato->ab)->where('level', '=', 2)->first();

            if($kato->level === 6) {
                $parent = $level_5 ? $level_5->te : $level_4->te;
                $kato->update([
                    'parent' => $parent,
                    'fullname_ru' => isset($level_2->name_ru).', '.isset($level_3->name_ru).', '.isset($level_4->name_ru).', '.isset($level_5->name_ru).', '.$kato->name_ru,
                    'fullname_kz' => isset($level_2->name_kz).', '.isset($level_3->name_kz).', '.isset($level_4->name_kz).', '.isset($level_5->name_kz).', '.$kato->name_kz,
                ]);
            } elseif($kato->level === 5) {
                $parent = $level_4 ? $level_4->te : $level_3->te;
                $kato->update([
                    'parent' => $parent,
                    'fullname_ru' => isset($level_2->name_ru).', '.isset($level_3->name_ru).', '.isset($level_4->name_ru).', '.$kato->name_ru,
                    'fullname_kz' => isset($level_2->name_kz).', '.isset($level_3->name_kz).', '.isset($level_4->name_kz).', '.$kato->name_kz,
                ]);
            } elseif($kato->level === 4) {
                $parent = $level_3 ? $level_3->te : $level_2->te;
                $kato->update([
                    'parent' => $parent,
                    'fullname_ru' => isset($level_2->name_ru).', '.isset($level_3->name_ru).', '.$kato->name_ru,
                    'fullname_kz' => isset($level_2->name_kz).', '.isset($level_3->name_kz).', '.$kato->name_kz,
                ]);
            } elseif($kato->level === 3) {
                $kato->update([
                    'parent' => $level_2->te,
                    'fullname_ru' => isset($level_2->name_ru).', '.$kato->name_ru,
                    'fullname_kz' => isset($level_2->name_kz).', '.$kato->name_kz,
                ]);
            }
        }
    }

});

Route::middleware('auth:sanctum')->group( function () {
    Route::controller(CurrencyController::class)->group(function () {
        Route::get('/currencies/{date}', 'getCurrencies')->name('get_currencies');
        Route::get('/currency/{name}', 'getCurrency')->name('get_currency');
    });
});
