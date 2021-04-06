<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class EnabledScope
 * @package App\Concerns
 */
class EnabledScope implements Scope
{
    use ScopeExtensible;

    protected array $extensions = [
        'enabled',
        'disabled',
    ];

    /**
     * @param Builder $builder
     * @param Model|EnableField $model
     * @return Builder
     */
    public function apply(Builder $builder, Model $model): Builder
    {
        return $builder->where($model->getQualifiedEnabledColumn(), $model->getEnableFieldScopeActivationValue());
    }

    public function addEnabled(Builder $builder): void
    {
        $builder->macro('enabled', function (Builder $builder, bool $enabled = true) {
            /**
             * @var EnableField $model
             */
            $model = $builder->getModel();
            return $builder->where($model->getQualifiedEnabledColumn(), "=", $enabled);
        });
    }

    public function addDisabled(Builder $builder): void
    {
        $builder->macro('disabled', function (Builder $builder, bool $disabled = true) {
            /**
             * @var EnableField $model
             */
            $model = $builder->getModel();
            return $builder->withoutGlobalScope($this)->where($model->getQualifiedEnabledColumn(), "=", !$disabled);
        });
    }
}
