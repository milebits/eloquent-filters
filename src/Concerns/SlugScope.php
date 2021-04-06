<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

/**
 * Class SlugScope
 * @package App\Concerns
 */
class SlugScope implements Scope
{
    use ScopeExtensible;

    protected array $extensions = [
        'slug', 'notSlug', "slugLike", "slugNotLike", "slugContains", "slugDoesntContain",
    ];

    /**
     * @param Builder $builder
     * @return void
     */
    public function addSlug(Builder $builder): void
    {
        $builder->macro('slug', function (Builder $builder, string $slug, string $column = null) {
            /**
             * @var SlugField $model
             */
            $model = $builder->getModel();
            return $builder->where($column ?? $model->getQualifiedSlugColumn(), '=', $slug);
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function addNotSlug(Builder $builder): void
    {
        $builder->macro('notSlug', function (Builder $builder, string $slug, string $column = null) {
            /**
             * @var SlugField $model
             */
            $model = $builder->getModel();
            return $builder->where($column ?? $model->getQualifiedSlugColumn(), '!=', $slug);
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function addSlugLike(Builder $builder): void
    {
        $builder->macro('slugLike', function (Builder $builder, string $slug, string $column = null) {
            /**
             * @var SlugField $model
             */
            $model = $builder->getModel();
            return $builder->where($column ?? $model->getQualifiedSlugColumn(), 'like', "$slug%");
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function addSlugNotLike(Builder $builder): void
    {
        $builder->macro('slugNotLike', function (Builder $builder, string $slug, string $column = null) {
            /**
             * @var SlugField $model
             */
            $model = $builder->getModel();
            return $builder->where($column ?? $model->getQualifiedSlugColumn(), 'notlike', "$slug%");
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function addSlugContains(Builder $builder): void
    {
        $builder->macro('slugContains', function (Builder $builder, string $slug, string $column = null) {
            /**
             * @var SlugField $model
             */
            $model = $builder->getModel();
            return $builder->where($column ?? $model->getQualifiedSlugColumn(), 'like', "%$slug%");
        });
    }

    /**
     * @param Builder $builder
     * @return void
     */
    public function addSlugDoesntContain(Builder $builder): void
    {
        $builder->macro('slugDoesntContain', function (Builder $builder, string $slug, string $column = null) {
            /**
             * @var SlugField $model
             */
            $model = $builder->getModel();
            return $builder->where($column ?? $model->getQualifiedSlugColumn(), 'notlike', "%$slug%");
        });
    }

    public function apply(Builder $builder, Model $model): void
    {

    }
}
