<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use function Milebits\Helpers\Helpers\constVal;

/**
 * Trait PhoneField
 * @package Milebits\Eloquent\Filters\Concerns
 * @mixin Model
 */
trait PhoneField
{
    public function initializePhoneField(): void
    {
        $this->mergeFillable(Arr::wrap($this->getPhoneColumn()));
    }

    /**
     * @return string
     */
    public function getPhoneColumn(): string
    {
        return constVal($this, 'PHONE_COLUMN', 'phone');
    }

    /**
     * @param Builder $builder
     * @param string $phone
     * @return Builder
     */
    public function scopePhoneNot(Builder $builder, string $phone): Builder
    {
        return $this->scopePhone($builder, $phone, '!=');
    }

    /**
     * @param Builder $builder
     * @param string $phone
     * @param string $operator
     * @return Builder
     */
    public function scopePhone(Builder $builder, string $phone, string $operator = '='): Builder
    {
        return $builder->where($this->decidePhoneColumn($builder), $operator, $phone);
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decidePhoneColumn(Builder $builder): string
    {
        return count(property_exists($builder, 'joins') ? $builder->joins : []) > 0
            ? $this->getQualifiedPhoneColumn()
            : $this->getPhoneColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedPhoneColumn(): string
    {
        return $this->qualifyColumn($this->getPhoneColumn());
    }

    /**
     * @param Builder $builder
     * @param string $phone
     * @return Builder
     */
    public function scopePhoneLike(Builder $builder, string $phone): Builder
    {
        return $this->scopePhone($builder, Str::of($phone)->append('%'), 'like');
    }

    /**
     * @param Builder $builder
     * @param string $phone
     * @return Builder
     */
    public function scopePhoneNotLike(Builder $builder, string $phone): Builder
    {
        return $this->scopePhone($builder, Str::of($phone)->append('%'), 'not like');
    }
}