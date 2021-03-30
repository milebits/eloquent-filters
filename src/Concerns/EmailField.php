<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use function Milebits\Helpers\Helpers\constVal;

/**
 * Trait EmailField
 * @package Milebits\Eloquent\Filters\Concerns
 * @mixin Model
 */
trait EmailField
{
    public function initializeEmailField(): void
    {
        $this->mergeFillable(Arr::wrap($this->getEmailColumn()));
        $this->mergeCasts([$this->getEmailColumn(), 'datetime']);
    }

    /**
     * @return string
     */
    public function getEmailColumn(): string
    {
        return constVal($this, 'EMAIL_COLUMN', 'email');
    }

    /**
     * @param Builder $builder
     * @param string $email
     * @param bool $not
     * @return Builder
     */
    public function scopeEmailNot(Builder $builder, string $email, bool $not = true): Builder
    {
        return $this->scopeEmail($builder, $email, $not ? '!=' : '=');
    }

    /**
     * @param Builder $builder
     * @param string $email
     * @param string $operator
     * @return Builder
     */
    public function scopeEmail(Builder $builder, string $email, string $operator = '='): Builder
    {
        return $builder->where($this->decideEmailColumn($builder), $operator, $email);
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideEmailColumn(Builder $builder): string
    {
        return count(property_exists($builder, 'joins') ? $builder->joins : []) > 0 ? $this->getQualifiedEmailColumn() : $this->getEmailColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedEmailColumn(): string
    {
        return $this->qualifyColumn($this->getEmailColumn());
    }

    /**
     * @param Builder $builder
     * @param string $email
     * @param bool $notLike
     * @return Builder
     */
    public function scopeEmailNotLike(Builder $builder, string $email, bool $notLike = true): Builder
    {
        return $this->scopeEmailLike($builder, $email, !$notLike);
    }

    /**
     * @param Builder $builder
     * @param string $email
     * @param bool $isLike
     * @return Builder
     */
    public function scopeEmailLike(Builder $builder, string $email, bool $isLike = true): Builder
    {
        return $this->scopeEmail($builder, $email, $isLike ? 'like' : 'not like');
    }
}