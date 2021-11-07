<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $code
 * @property int $from_city_id
 * @property int $to_city_id
 * @property int $places
 * @property int $free_places
 *
 * @method static bool insert(array $values)
 * @method Trip find(int $id)
 * @method Trip firstWhere($column, $operator = null, $value = null, $boolean = 'and')
 */
class Trip extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'from_city_id',
        'to_city_id',
        'places',
        'free_places',
    ];

    /**
     * @return BelongsTo
     */
    public function from(): BelongsTo
    {
        return $this->belongsTo(City::class, 'from_city_id');
    }

    /**
     * @return BelongsTo
     */
    public function to(): BelongsTo
    {
        return $this->belongsTo(City::class, 'to_city_id');
    }
}
