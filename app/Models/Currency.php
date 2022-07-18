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
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency query()
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Currency whereName($value)
 * @mixin \Eloquent
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
