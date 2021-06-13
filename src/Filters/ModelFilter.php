<?php


namespace Milebits\Eloquent\Filters\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Milebits\Eloquent\Filters\Concerns\HandlesKeys;
use Milebits\Eloquent\Filters\Concerns\HandlesRequests;


/**
 * Class Filter
 * @package Milebits\Eloquent\Filters\Filters
 */
class ModelFilter
{
    use HandlesRequests, HandlesKeys;

    protected ?string $defaultKey = null;

    protected bool $final_filter = false;

    protected ?array $extraKeyAttributes = null;
    protected bool $validateAllKeyAttributes = false;

    /**
     * @param Builder $builder
     * @param Closure $next
     * @return Closure|Builder|mixed
     */
    public function handle(Builder $builder, Closure $next): Builder
    {
        return $this->condition()
            ? ($this->final_filter
                ? $this->apply($next($builder))
                : $next($this->apply($builder)))
            : $next($builder);
    }

    /**
     * @return bool
     */
    public function condition(): bool
    {
        if (!is_null($this->extraKeyAttributes) && $this->validateAllKeyAttributes)
            return $this->requestHasAll($this->keys() ? $this->keys() : []);

        if (!is_null($this->extraKeyAttributes) && !$this->validateAllKeyAttributes)
            return $this->requestHasAtLeastOneOf($this->keys() ? $this->keys() : []);

        return $this->requestHas($this->key());
    }

    /**
     * @param Builder $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        return $builder->where($this->key(), $this->keyValue());
    }
}