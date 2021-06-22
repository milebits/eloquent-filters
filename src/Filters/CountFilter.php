<?php

namespace Milebits\Eloquent\Filters\Filters;

use Illuminate\Database\Eloquent\Builder;


/**
 * Class CountFilter
 * @package Milebits\Eloquent\Filters\Filters
 */
class CountFilter extends ModelFilter
{
    public function apply(Builder $builder): Builder
    {
        return $builder->whereBetween($this->key(), [
            $this->keyValue("min", $this->keyValue(default: 0)),
            $this->keyValue("max", 9 * 10 ^ 9),
        ]);
    }
}