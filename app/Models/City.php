<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $country
 *
 * @method static bool insert(array $values)
 * @method static City firstWhere($column, $operator = null, $value = null, $boolean = 'and')
 */
class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'country',
    ];

    /**
     * @inheritdoc
     * @see HasTimestamps::$timestamps
     *
     * @var bool
     */
    public $timestamps = false;
}
