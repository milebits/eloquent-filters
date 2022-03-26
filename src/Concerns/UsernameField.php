<?php

namespace Milebits\Eloquent\Filters\Concerns;

use function constVal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @mixin Model
 */
trait UsernameField
{
    public function initializeUsernameField(): void
    {
        $this->mergeFillable([$this->getUsernameColumn()]);
    }

    /**
     * @return string
     */
    public function getUsernameColumn(): string
    {
        return constVal($this, 'USERNAME_COLUMN', 'username');
    }

    /**
     * @param Builder $builder
     * @param string  $username
     *
     * @return Builder
     */
    public function scopeUsernameNot(Builder $builder, string $username): Builder
    {
        return $this->scopeUsername($builder, $username, '!=');
    }

    /**
     * @param Builder $builder
     * @param string  $username
     * @param string  $operator
     *
     * @return Builder
     */
    public function scopeUsername(Builder $builder, string $username, string $operator = '='): Builder
    {
        return $builder->where($this->decideUsernameColumn($builder), $operator, $username);
    }

    /**
     * @param Builder $builder
     *
     * @return string
     */
    public function decideUsernameColumn(Builder $builder): string
    {
        return count((property_exists($builder, 'joins') ? $builder->joins : [])) > 0
            ? $this->getQualifiedUsernameColumn()
            : $this->getUsernameColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedUsernameColumn(): string
    {
        return $this->qualifyColumn($this->getUsernameColumn());
    }

    /**
     * @param Builder $builder
     * @param string  $username
     *
     * @return Builder
     */
    public function scopeUsernameLike(Builder $builder, string $username): Builder
    {
        return $this->scopeUsername($builder, Str::of($username)->append('%'), 'like');
    }

    /**
     * @param Builder $builder
     * @param string  $username
     *
     * @return Builder
     */
    public function scopeUsernameNotLike(Builder $builder, string $username): Builder
    {
        return $this->scopeUsername($builder, Str::of($username)->append('%'), 'not like');
    }
}
