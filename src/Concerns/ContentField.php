<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use function Milebits\Helpers\Helpers\constVal;

/**
 * Trait ContentField
 * @package Milebits\Eloquent\Filters\Concerns
 *
 * @mixin Model
 */
trait ContentField
{
    public function initializeContentField(): void
    {
        $this->mergeFillable(Arr::wrap($this->getContentColumn()));
    }

    /**
     * @return string
     */
    public function getContentColumn(): string
    {
        return constVal($this, 'TITLE_COLUMN', 'content');
    }

    /**
     * @param Builder $builder
     * @param string $content
     * @param string $operator
     * @return Builder
     */
    public function scopeContentDoesntContain(Builder $builder, string $content, string $operator = "notlike"): Builder
    {
        return $this->scopeContentContains($builder, $content, $operator);
    }

    /**
     * @param Builder $builder
     * @param string $content
     * @param string $operator
     * @return Builder
     */
    public function scopeContentContains(Builder $builder, string $content, string $operator = "like"): Builder
    {
        return $builder->where($this->decideContentColumn($builder), $operator, "%$content%");
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideContentColumn(Builder $builder): string
    {
        return count(property_exists($builder, 'joins') ? $builder->joins : []) ? $this->getQualifiedContentColumn() : $this->getContentColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedContentColumn(): string
    {
        return $this->qualifyColumn($this->getContentColumn());
    }
}