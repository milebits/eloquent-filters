<?php

namespace Milebits\Eloquent\Filters\Filters;

use Illuminate\Database\Eloquent\Builder;


/**
 * Class ActiveFilter
 * @package App\Filters
 */
class ActiveFilter extends ModelFilter
{
    /**
     * The filter to apply to the model builder from the pipeline
     *
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        return $builder->withoutGlobalScope("not_active")->where($this->key(), '=', (bool)$this->keyValue());
    }
}
