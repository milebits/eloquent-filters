<?php

namespace Milebits\Eloquent\Filters\Filters;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class PhoneIsVerifiedFilter.
 */
class PhoneVerifiedFilter extends ModelFilter
{
    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        return $builder->verifiedPhone((bool) $this->keyValue());
    }
}
