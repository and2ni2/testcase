<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Models\Kato
 *
 * @mixin Builder
 * @property int                                                                                                            $id
 * @property string                                                                                                         $te
 * @property string                                                                                                         $ab
 * @property string                                                                                                         $cd
 * @property string                                                                                                         $ef
 * @property string                                                                                                         $hij
 * @property int                                                                                                            $k
 * @property string|null                                                                                                    $parent
 * @property int|null                                                                                                       $level
 * @property string                                                                                                         $name_ru
 * @property string                                                                                                         $name_kz
 * @property string|null                                                                                                    $fullname_ru
 * @property string|null                                                                                                    $fullname_kz
 * @property Carbon|null                                                                                                    $start_date
 * @property Carbon|null                                                                                                    $end_date
 * @property Carbon|null                                                                                                    $created_at
 * @property Carbon|null                                                                                                    $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Kato newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kato newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Kato query()
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereFullnameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereFullnameKz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereNameKz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereK($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereHij($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereEf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereCd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereAb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Kato whereTe($value)
 * @mixin \Eloquent
 */

class Kato extends Model
{
    use HasFactory;

    protected $table = 'kato';

    protected $fillable = [
        'te',
        'ab',
        'cd',
        'ef',
        'hij',
        'k',
        'parent',
        'level',
        'name_ru',
        'name_kz',
        'fullname_ru',
        'fullname_kz',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];


    /**
     * Get all Kato childrens record by 'te'
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->HasMany(self::class, 'parent', 'te')->with('children');
    }

}
