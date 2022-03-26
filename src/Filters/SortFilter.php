<?php

namespace Milebits\Eloquent\Filters\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class SortFilter.
 */
class SortFilter extends ModelFilter
{
    /**
     * The filter to apply to the model builder from the pipeline.
     *
     * @param Builder $builder
     *
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        return $builder->orderBy(
            $this->keyValue('column', $builder->getModel()->getKey()),
            (string) $this->keyValue('order', 'asc')
        );
    }
}
