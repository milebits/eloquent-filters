<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pipeline\Pipeline;
use function Milebits\Eloquent\Filters\Helpers\constant_value;

/**
 * Trait LaraFilters
 *
 * @mixin Model
 */
trait Filterable
{
    /**
     * @param array $filters
     * @param bool $only
     * @param Closure|null $then
     * @return Builder
     */
    public static function filtered(array $filters = [], bool $only = false, Closure $then = null): Builder
    {
        $result = app(Pipeline::class)->send(static::query())
            ->through(
                $only
                    ? $filters ?? []
                    : array_merge(constant_value(self::class, "filters", []), $filters)
            );

        return $result->then($then ?? function ($passable) {
                return $passable;
            });
    }
}