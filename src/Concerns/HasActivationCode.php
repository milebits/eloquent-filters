<?php


namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use function Milebits\Helpers\Helpers\constVal;

/**
 * Trait HasActivationCode
 * @package Milebits\Eloquent\Filters\Concerns
 *
 * @mixin Model
 * @property string $activation_code
 * @property Carbon $activated_at
 */
trait HasActivationCode
{
    public static function bootHasActivationCode(): void
    {
        static::addGlobalScope('whereNullActivatedAt', function (Builder $builder) {
            return $builder->whereNull($builder->getModel()->decideActivatedAtColumn($builder));
        });
    }

    public function initializeHasActivationCode(): void
    {
        $this->mergeFillable([$this->getActivationCodeColumn()]);
        $this->mergeCasts([$this->getActivatedAtColumn() => 'datetime']);
    }

    /**
     * @return string
     */
    public function getActivationCodeColumn(): string
    {
        return constVal($this, 'ACTIVATION_CODE_COLUMN', 'activation_code');
    }

    /**
     * @return string
     */
    public function getActivatedAtColumn(): string
    {
        return constVal($this, 'ACTIVATED_AT_COLUMN', 'activated_at');
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideActivatedAtColumn(Builder $builder): string
    {
        return count((array)(property_exists($builder, 'joins') ? $builder->joins : [])) > 0
            ? $this->getQualifiedActivatedAtColumn()
            : $this->getActivatedAtColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedActivatedAtColumn(): string
    {
        return $this->qualifyColumn($this->getActivatedAtColumn());
    }

    /**
     * @param Builder $builder
     * @param string $activationCode
     * @return Builder
     */
    public function scopeActivationCode(Builder $builder, string $activationCode): Builder
    {
        return $builder->where(function (Builder $builder) use ($activationCode) {
            return $builder->where($this->decideActivationCodeColumn($builder), '=', $activationCode);
        });
    }

    /**
     * @param Builder $builder
     * @return string
     */
    public function decideActivationCodeColumn(Builder $builder): string
    {
        return count((array)(property_exists($builder, 'joins') ? $builder->joins : [])) > 0
            ? $this->getQualifiedActivationCodeColumn()
            : $this->getActivationCodeColumn();
    }

    /**
     * @return string
     */
    public function getQualifiedActivationCodeColumn(): string
    {
        return $this->qualifyColumn($this->getActivationCodeColumn());
    }

    /**
     * @param Builder $builder
     * @param string $activationCode
     * @return Builder
     */
    public function scopeActivationCodeNot(Builder $builder, string $activationCode): Builder
    {
        return $builder->where(function (Builder $builder) use ($activationCode) {
            return $builder->where($this->decideActivationCodeColumn($builder), '!=', $activationCode);
        });
    }

    /**
     * @param Builder $builder
     * @param string $activationCode
     * @return Builder
     */
    public function scopeOrActivationCode(Builder $builder, string $activationCode): Builder
    {
        return $builder->orWhere(function (Builder $builder) use ($activationCode) {
            return $builder->where($this->decideActivationCodeColumn($builder), '=', $activationCode);
        });
    }

    /**
     * @param Builder $builder
     * @param string $activationCode
     * @return Builder
     */
    public function scopeOrActivationCodeNot(Builder $builder, string $activationCode): Builder
    {
        return $builder->orWhere(function (Builder $builder) use ($activationCode) {
            return $builder->where($this->decideActivationCodeColumn($builder), '!=', $activationCode);
        });
    }

    public function activate()
    {
        $this->activated_at = now();
        return $this;
    }

    /**
     * @param int $length
     * @return string
     */
    public function generateActivationCode(int $length = 16): string
    {
        return Str::random($length);
    }
}