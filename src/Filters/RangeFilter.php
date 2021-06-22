<?php


namespace Milebits\Eloquent\Filters\Filters;


use Illuminate\Database\Eloquent\Builder;

class RangeFilter extends ModelFilter
{
    protected int|float $maxValue = 9000;

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        return $builder->whereBetween($this->key(), [
            $this->keyValue("min", $this->keyValue(default: 0)),
            $this->keyValue("max", 9 * 10 ^ 9),
        ]);
    }
}