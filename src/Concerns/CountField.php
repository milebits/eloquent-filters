<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\Pure;
use function constVal;

/**
 * Trait CountField
 * @package Milebits\Eloquent\Filters\Concerns
 * @mixin Model
 */
trait CountField
{
    public function initializeCountField()
    {
        $this->mergeFillable([$this->getCountColumn()]);
    }

    /**
     * @return string
     */
    #[Pure] public function getCountColumn(): string
    {
        return constVal($this, "COUNT_COLUMN", "count");
    }

    /**
     * @param Builder $builder
     * @param int $count
     * @param string $operator
     * @return Builder
     */
    public function scopeCountFieldNot(Builder $builder, int $count, string $operator = "!="): Builder
    {
        return $this->scopeCountField($builder, $count, $operator);
    }

    /**
     * @param Builder $builder
     * @param int $count
     * @param string $operator
     * @return Builder
     */
    public function scopeCountField(Builder $builder, int $count, string $operator = "="): Builder
    {
        return $builder->where($this->decideCountColumn($builder), $operator, $count);
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideCountColumn(Builder $builder): string
    {
        return count(property_exists($builder, 'joins') ? $builder->joins : []) ? $this->getQualifiedCountColumn() : $this->getCountColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedCountColumn(): string
    {
        return $this->qualifyColumn($this->getCountColumn());
    }

    /**
     * @return $this
     */
    public function incrementCount(): self
    {
        $this->{$this->getCountColumn()}++;
        return $this;
    }

    /**
     * @return $this
     */
    public function decrementCount(): self
    {
        $this->{$this->getCountColumn()}--;
        return $this;
    }
}