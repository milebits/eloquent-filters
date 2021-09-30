<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use function constVal;

/**
 * Trait DescriptionField
 * @package Milebits\Eloquent\Filters\Concerns
 *
 * @mixin Model
 */
trait DescriptionField
{
    public function initializeDescriptionField(): void
    {
        $this->mergeFillable(Arr::wrap($this->getDescriptionColumn()));
    }

    /**
     * @return string
     */
    public function getDescriptionColumn(): string
    {
        return constVal($this, 'TITLE_COLUMN', 'description');
    }

    /**
     * @param Builder $builder
     * @param string $description
     * @param string $operator
     * @return Builder
     */
    public function scopeDescriptionDoesntContain(Builder $builder, string $description, string $operator = "notlike"): Builder
    {
        return $this->scopeDescriptionContains($builder, $description, $operator);
    }

    /**
     * @param Builder $builder
     * @param string $description
     * @param string $operator
     * @return Builder
     */
    public function scopeDescriptionContains(Builder $builder, string $description, string $operator = "like"): Builder
    {
        return $builder->where($this->decideDescriptionColumn($builder), $operator, "%$description%");
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideDescriptionColumn(Builder $builder): string
    {
        return count(property_exists($builder, 'joins') ? $builder->joins : []) ? $this->getQualifiedDescriptionColumn() : $this->getDescriptionColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedDescriptionColumn(): string
    {
        return $this->qualifyColumn($this->getDescriptionColumn());
    }
}