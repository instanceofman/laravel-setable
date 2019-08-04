<?php


namespace Isofman\LaravelSetable;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * @method static Eloquent firstOrCreate(array $array)
 * @method static Eloquent first()
 * @method static Eloquent firstOrNew(array $array)
 */
class Setting extends Eloquent
{
    protected $guarded = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('setable.table');
    }
}