<?php

namespace Milebits\Eloquent\Filters\Filters;

use Illuminate\Database\Eloquent\Builder;


/**
 * Class EnableFilter
 * @package App\Filters
 */
class EnableFilter extends ModelFilter
{
    /**
     * The filter to apply to the model builder from the pipeline
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        return $builder->where($this->key(), '=', (bool)$this->keyValue());
    }
}
