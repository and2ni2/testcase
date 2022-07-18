<?php

namespace App\Modules\Kato;


use App\Models\Kato;
use Carbon\Carbon;

/**
 * Interface for Kato imports
 */
class Import
{
    /**
     * Import file from xls like collection
     *
     * @param object $collection
     * @param string $type
     *
     */
    public function importFromFile(object $collection, string $type)
    {
        foreach ($collection as $item) {
            if ($item[0] && $item[0] !== 'te') {
                $level = 2;
                $parent = null;
                if ($item[4] !== '000' && (intval($item[4]) % 100 !== 0)) {
                    $level = 6;
                } elseif ($item[4] !== '000' && (intval($item[4]) % 100 === 0)) {
                    $level = 5;
                } elseif ($item[4] === '000' && $item[3] !== '00') {
                    $level = 4;
                } elseif ($item[3] === '00' && $item[2] !== '00') {
                    $level = 3;
                }

                if ($type === 'import') {
                    Kato::create([
                        'te' => $item[0],
                        'ab' => $item[1],
                        'cd' => $item[2],
                        'ef' => $item[3],
                        'hij' => $item[4],
                        'k' => $item[5],
                        'name_ru' => $item[7],
                        'name_kz' => $item[6],
                        'start_date' => \Carbon\Carbon::now()->format('Y-m-d'),
                        'level' => $level,
                        'parent' => $parent,
                        'fullname_ru' => $item[7],
                        'fullname_kz' => $item[6],
                    ]);
                } elseif ($type === 'update') {
                    $today_date = Carbon::now()->format('Y-m-d');
                    Kato::updateOrCreate(
                        [
                            'te'         => $item[0],
                            'end_date' => $today_date
                        ],
                        [   'ab'         => $item[1],
                            'cd'         => $item[2],
                            'ef'         => $item[3],
                            'hij'        => $item[4],
                            'k'          => $item[5],
                            'name_ru'    => $item[7],
                            'name_kz'    => $item[6],
                            'level'      => $level,
                            'parent'     => $parent,
                            'fullname_ru' => $item[7],
                            'fullname_kz' => $item[6],
                        ]
                    );
                }
            }
        }
    }

    /**
     * Update some columns in table after import from xls
     *
     * @param object $katos
     * @param string $type
     *
     */
    public function katoPostUpdate(object $katos, string $type)
    {
        foreach($katos as $kato) {
            $level_5 = Kato::where('ab', $kato->ab)->where('cd', $kato->cd)->where('ef', $kato->ef)->where('hij', '=', $kato->hij[0].'00')->where('level', '=', 5)->first();
            $level_4 = Kato::where('ab', $kato->ab)->where('cd', $kato->cd)->where('ef', $kato->ef)->where('level', '=', 4)->first();
            $level_3 = Kato::where('ab', $kato->ab)->where('cd', $kato->cd)->where('level', '=', 3)->first();
            $level_2 = Kato::where('ab', $kato->ab)->where('level', '=', 2)->first();
            $lv5_ru = $level_5 ? $level_5->name_ru.', ' : '';
            $lv5_kz = $level_5 ? $level_5->name_kz.', ' : '';
            $lv4_ru = $level_4 ? $level_4->name_ru.', ' : '';
            $lv4_kz = $level_4 ? $level_4->name_kz.', ' : '';
            $lv3_ru = $level_3 ? $level_3->name_ru.', ' : '';
            $lv3_kz = $level_3 ? $level_3->name_kz.', ' : '';
            $today_date = Carbon::now()->format('Y-m-d');

            if ($type === 'import') {
                if($kato->level === 6 && ($level_5 || $level_4)) {
                    $parent = $level_5 ? $level_5->te : $level_4->te;
                    $kato->update([
                        'parent' => $parent,
                        'fullname_ru' => $level_2->name_ru.', '.$lv3_ru.$lv4_ru.$lv5_ru.$kato->name_ru,
                        'fullname_kz' => $level_2->name_kz.', '.$lv3_kz.$lv4_kz.$lv5_kz.$kato->name_kz
                    ]);
                } elseif($kato->level === 5 && ($level_4 || $level_3)) {
                    $parent = $level_4 ? $level_4->te : $level_3->te;
                    $kato->update([
                        'parent' => $parent,
                        'fullname_ru' => $level_2->name_ru.', '.$lv3_ru.$lv4_ru.$kato->name_ru,
                        'fullname_kz' => $level_2->name_kz.', '.$lv3_kz.$lv4_kz.$kato->name_kz,
                    ]);
                } elseif($kato->level === 4 && ($level_3 || $level_2)) {
                    $parent = $level_3 ? $level_3->te : $level_2->te;
                    $kato->update([
                        'parent' => $parent,
                        'fullname_ru' => $level_2->name_ru.', '.$lv3_ru.$kato->name_ru,
                        'fullname_kz' => $level_2->name_kz.', '.$lv3_kz.$kato->name_kz,
                    ]);
                } elseif($kato->level === 3 && $level_2) {
                    $kato->update([
                        'parent' => $level_2->te,
                        'fullname_ru' => $level_2->name_ru.', '.$kato->name_ru,
                        'fullname_kz' => $level_2->name_kz.', '.$kato->name_kz,
                    ]);
                }
            } elseif ($type === 'update') {
                if($kato->level === 6 && ($level_5 || $level_4)) {
                    $parent = $level_5 ? $level_5->te : $level_4->te;
                    $kato->update([
                        'start_date' => $kato->start_date ?: $today_date,
                        'end_date' => null,
                        'parent' => $parent,
                        'fullname_ru' => $level_2->name_ru.', '.$lv3_ru.$lv4_ru.$lv5_ru.$kato->name_ru,
                        'fullname_kz' => $level_2->name_kz.', '.$lv3_kz.$lv4_kz.$lv5_kz.$kato->name_kz
                    ]);
                } elseif($kato->level === 5 && ($level_4 || $level_3)) {
                    $parent = $level_4 ? $level_4->te : $level_3->te;
                    $kato->update([
                        'start_date' => $kato->start_date ?: $today_date,
                        'end_date' => null,
                        'parent' => $parent,
                        'fullname_ru' => $level_2->name_ru.', '.$lv3_ru.$lv4_ru.$kato->name_ru,
                        'fullname_kz' => $level_2->name_kz.', '.$lv3_kz.$lv4_kz.$kato->name_kz,
                    ]);
                } elseif($kato->level === 4 && ($level_3 || $level_2)) {
                    $parent = $level_3 ? $level_3->te : $level_2->te;
                    $kato->update([
                        'start_date' => $kato->start_date ?: $today_date,
                        'end_date' => null,
                        'parent' => $parent,
                        'fullname_ru' => $level_2->name_ru.', '.$lv3_ru.$kato->name_ru,
                        'fullname_kz' => $level_2->name_kz.', '.$lv3_kz.$kato->name_kz,
                    ]);
                } elseif($kato->level === 3 && $level_2) {
                    $kato->update([
                        'start_date' => $kato->start_date ?: $today_date,
                        'end_date' => null,
                        'parent' => $level_2->te,
                        'fullname_ru' => $level_2->name_ru.', '.$kato->name_ru,
                        'fullname_kz' => $level_2->name_kz.', '.$kato->name_kz,
                    ]);
                } elseif($kato->level === 2) {
                    $kato->update([
                        'start_date' => $kato->start_date ?: $today_date,
                        'end_date' => null,
                    ]);
                }
            }
        }
    }


}
