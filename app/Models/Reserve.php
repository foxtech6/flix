<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $trip_id
 * @property string $email
 * @property int $places
 *
 * @method Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method Reserve find(int $id)
 * @method Reserve create(array $attributes = [])
 * @method Reserve firstWhere($column, $operator = null, $value = null, $boolean = 'and')
 */
class Reserve extends Model
{
    /**
     * @var string
     */
    public const MODE_REMOVE_PLACES = 'remove';

    /**
     * @var string
     */
    public const MODE_ADD_PLACES = 'add';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trip_id',
        'email',
        'places',
    ];

    /**
     * @return BelongsTo
     */
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
}
