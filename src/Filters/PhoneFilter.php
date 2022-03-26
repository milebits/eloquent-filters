<?php

namespace Milebits\Eloquent\Filters\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class PhoneFilter.
 */
class PhoneFilter extends ModelFilter
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
        return $builder->phone((string) $this->keyValue());
    }
}
