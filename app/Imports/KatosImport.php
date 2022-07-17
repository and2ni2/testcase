<?php

namespace App\Imports;

use App\Models\Kato;
use Maatwebsite\Excel\Concerns\ToModel;

class KatosImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Kato([
            'te'      => $row[0],
            'ab'      => $row[1],
            'cd'      => $row[2],
            'ef'      => $row[3],
            'hij'     => $row[4],
            'k'       => $row[5],
            'name_ru' => $row[7],
            'name_kz' => $row[6],
        ]);
    }
}
