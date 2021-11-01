<?php


namespace Milebits\Eloquent\Filters\Concerns;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use function constVal;

/**
 * Trait ActiveField
 * @package Milebits\Eloquent\Filters\Concerns
 * @mixin Model
 */
trait ActiveField
{
    public static function bootActiveField(): void
    {
        if (constVal(static::class, 'ACTIVE_COLUMN_ENABLED', true))
            static::addGlobalScope('not_active', function ($builder) {
                return $builder->where(
                    $builder->getModel()->decideActiveColumn($builder), '=',
                    constVal($builder->getModel(), 'ACTIVE_DEFAULT_VALUE', true)
                );
            });
    }

    public function initializeActiveField(): void
    {
        $this->mergeFillable([$this->getActiveColumn()]);
    }

    /**
     * @return string
     */
    public function getActiveColumn(): string
    {
        return constVal($this, "ACTIVE_COLUMN", 'active');
    }

    /**
     * @param Builder $builder
     * @param bool $activated
     * @return Builder
     */
    public function scopeWhereActivated(Builder $builder, bool $activated = true): Builder
    {
        return $builder->withoutGlobalScope('not_active')->where(function (Builder $builder) use ($activated) {
            return $builder->where($this->decideActiveColumn($builder), '=', $activated);
        });
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideActiveColumn(Builder $builder): string
    {
        return count((array)(property_exists($builder, 'joins') ? $builder->joins : [])) > 0
            ? $this->getQualifiedActiveColumn()
            : $this->getActiveColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedActiveColumn(): string
    {
        return $this->qualifyColumn($this->getActiveColumn());
    }

    /**
     * @param Builder $builder
     * @param bool $deactivated
     * @return Builder
     */
    public function scopeWhereDeactivated(Builder $builder, bool $deactivated = true): Builder
    {
        return $builder->withoutGlobalScope('whereNotActivated')->where(function (Builder $builder) use ($deactivated) {
            return $builder->where($this->decideActiveColumn($builder), '=', !$deactivated);
        });
    }

    /**
     * @return $this
     */
    public function activate(): self
    {
        $this->{$this->getActiveColumn()} = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function deactivate(): self
    {
        $this->{$this->getActiveColumn()} = false;
        return $this;
    }
}
