<?php


namespace Milebits\Eloquent\Filters\Concerns;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use function Milebits\Helpers\Helpers\constVal;

/**
 * Trait Activatable
 * @package Milebits\Eloquent\Filters\Concerns
 * @mixin Model
 */
trait Activatable
{
    public static function bootActivatable(): void
    {
        if (constVal(static::class, 'ACTIVATABLE_ENABLED', true))
            static::addGlobalScope('whereNotActivated', function (Builder $builder) {
                return $builder->where(
                    $builder->getModel()->decideActivatableColumn($builder), '=',
                    constVal($builder->getModel(), 'ACTIVATED_ACTIVATION_VALUE', true)
                );
            });
    }

    public function initializeActivatable(): void
    {
        $this->mergeFillable([$this->getActivatedColumn()]);
    }

    /**
     * @return string
     */
    public function getActivatedColumn(): string
    {
        return constVal($this, "ACTIVATED_COLUMN", 'activated');
    }

    /**
     * @param Builder $builder
     * @param bool $activated
     * @return Builder
     */
    public function scopeWhereActivated(Builder $builder, bool $activated = true): Builder
    {
        return $builder->withoutGlobalScope('whereNotActivated')->where(function (Builder $builder) use ($activated) {
            return $builder->where($this->decideActivatableColumn($builder), '=', $activated);
        });
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideActivatableColumn(Builder $builder): string
    {
        return count((array)(property_exists($builder, 'joins') ? $builder->joins : [])) > 0
            ? $this->getQualifiedActivatedColumn()
            : $this->getActivatedColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedActivatedColumn(): string
    {
        return $this->qualifyColumn($this->getActivatedColumn());
    }

    /**
     * @param Builder $builder
     * @param bool $deactivated
     * @return Builder
     */
    public function scopeWhereDeactivated(Builder $builder, bool $deactivated = true): Builder
    {
        return $builder->withoutGlobalScope('whereNotActivated')->where(function (Builder $builder) use ($deactivated) {
            return $builder->where($this->decideActivatableColumn($builder), '=', !$deactivated);
        });
    }

    /**
     * @return $this
     */
    public function activate(): self
    {
        $this->{$this->getActivatedColumn()} = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function deactivate(): self
    {
        $this->{$this->getActivatedColumn()} = false;
        return $this;
    }
}