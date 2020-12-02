<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class EnableableScope
 * @package App\Concerns
 */
class EnableableScope implements Scope
{
    use ScopeExtensible;

    protected array $extensions = [
        'enabled',
        'disabled',
    ];

    /**
     * @param Builder $builder
     * @param Model|Enableable $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model)
    {
        return $builder->where($model->getQualifiedEnabledColumn(), $model->getEnableableScopeActivationValue());
    }

    public function addEnabled(Builder $builder): void
    {
        $builder->macro('enabled', function (Builder $builder, bool $enabled = true) {
            /**
             * @var Enableable $model
             */
            $model = $builder->getModel();
            return $builder->where($model->getQualifiedEnabledColumn(), "=", $enabled);
        });
    }

    public function addDisabled(Builder $builder): void
    {
        $builder->macro('disabled', function (Builder $builder, bool $disabled = true) {
            /**
             * @var Enableable $model
             */
            $model = $builder->getModel();
            return $builder->withoutGlobalScope($this)->where($model->getQualifiedEnabledColumn(), "=", !$disabled);
        });
    }
}
