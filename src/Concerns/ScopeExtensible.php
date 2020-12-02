<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Trait Scope
 * @package App\Concerns
 *
 * @mixin Model
 * @mixin Builder
 * @property array|string[] $extensions
 */
trait ScopeExtensible
{
    /**
     * @param Builder $builder
     * @return void
     */
    public function extend(Builder $builder): void
    {
        collect($this->extensions)->each(function (string $functionName) use ($builder) {
            $this->{'add' . Str::ucfirst(Str::camel($functionName))}($builder);
        });
    }
}
