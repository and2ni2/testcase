<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Currency
 *
 * @mixin Builder
 * @property int                                                                                                            $id
 * @property string                                                                                                         $name
 * @property string|null                                                                                                    $rate
 * @property Carbon|null                                                                                                    $date
 * @property Carbon|null                                                                                                    $created_at
 * @property Carbon|null                                                                                                    $updated_at
 */

class Currency extends Model
{
    use HasFactory;

    protected $table = 'currency';

    protected $fillable = [
        'name',
        'rate',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

}
