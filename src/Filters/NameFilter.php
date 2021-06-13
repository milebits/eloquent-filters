<?php

namespace Milebits\Eloquent\Filters\Filters;

use Illuminate\Database\Eloquent\Builder;


/**
 * Class NameFilter
 * @package App\Filters
 */
class NameFilter extends ModelFilter
{
    /**
     * The filter to apply to the model builder from the pipeline
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        return $builder->where($this->key(), 'like', "%" . $this->keyValue() . "%");
    }
}
