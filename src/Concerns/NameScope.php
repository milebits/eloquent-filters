<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class NameScope.
 */
class NameScope implements Scope
{
    use ScopeExtensible;

    protected array $extensions = [
        'name', 'notName', 'nameLike', 'nameNotLike', 'nameContains', 'nameDoesntContain',
    ];

    /**
     * @param Builder $builder
     *
     * @return void
     */
    public function addName(Builder $builder): void
    {
        $builder->macro('name', function (Builder $builder, string $name, string $column = null) {
            /**
             * @var NameField $model
             */
            $model = $builder->getModel();

            return $builder->where($column ?? $model->getQualifiedNameColumn(), $name);
        });
    }

    /**
     * @param Builder $builder
     *
     * @return void
     */
    public function addNotName(Builder $builder): void
    {
        $builder->macro('notName', function (Builder $builder, string $name, string $column = null) {
            /**
             * @var NameField $model
             */
            $model = $builder->getModel();

            return $builder->where($column ?? $model->getQualifiedNameColumn(), '!=', $name);
        });
    }

    /**
     * @param Builder $builder
     *
     * @return void
     */
    public function addNameLike(Builder $builder): void
    {
        $builder->macro('nameLike', function (Builder $builder, string $name, string $column = null) {
            /**
             * @var NameField $model
             */
            $model = $builder->getModel();

            return $builder->where($column ?? $model->getQualifiedNameColumn(), 'like', "$name%");
        });
    }

    /**
     * @param Builder $builder
     *
     * @return void
     */
    public function addNameNotLike(Builder $builder): void
    {
        $builder->macro('nameNotLike', function (Builder $builder, string $name, string $column = null) {
            /**
             * @var NameField $model
             */
            $model = $builder->getModel();

            return $builder->where($column ?? $model->getQualifiedNameColumn(), 'notlike', "$name%");
        });
    }

    /**
     * @param Builder $builder
     *
     * @return void
     */
    public function addNameContains(Builder $builder): void
    {
        $builder->macro('nameContains', function (Builder $builder, string $name, string $column = null) {
            /**
             * @var NameField $model
             */
            $model = $builder->getModel();

            return $builder->where($column ?? $model->getQualifiedNameColumn(), 'like', "%$name%");
        });
    }

    /**
     * @param Builder $builder
     *
     * @return void
     */
    public function addNameDoesntContain(Builder $builder): void
    {
        $builder->macro('nameDoesntContain', function (Builder $builder, string $name, string $column = null) {
            /**
             * @var NameField $model
             */
            $model = $builder->getModel();

            return $builder->where($column ?? $model->getQualifiedNameColumn(), 'notlike', "%$name%");
        });
    }

    public function apply(Builder $builder, Model $model): void
    {
    }
}
