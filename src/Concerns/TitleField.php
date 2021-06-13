<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use function Milebits\Helpers\Helpers\constVal;

/**
 * Trait TitleField
 * @package Milebits\Eloquent\Filters\Concerns
 *
 * @mixin Model
 */
trait TitleField
{
    public function initializeTitleField(): void
    {
        $this->mergeFillable(Arr::wrap($this->getTitleColumn()));
    }

    /**
     * @return string
     */
    public function getTitleColumn(): string
    {
        return constVal($this, 'TITLE_COLUMN', 'title');
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideTitleColumn(Builder $builder): string
    {
        return count(property_exists($builder, 'joins') ? $builder->joins : []) ? $this->getQualifiedTitleColumn() : $this->getTitleColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedTitleColumn(): string
    {
        return $this->qualifyColumn($this->getTitleColumn());
    }

    /**
     * @param Builder $builder
     * @param string $title
     * @param string $operator
     * @return Builder
     */
    public function scopeTitleContains(Builder $builder, string $title, string $operator = "like"): Builder
    {
        return $builder->where($this->decideTitleColumn($builder), $operator, "%$title%");
    }

    /**
     * @param Builder $builder
     * @param string $title
     * @param string $operator
     * @return Builder
     */
    public function scopeTitleDoesntContain(Builder $builder, string $title, string $operator = "notlike"): Builder
    {
        return $this->scopeTitleContains($builder, $title, $operator);
    }
}