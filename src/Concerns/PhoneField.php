<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use function constVal;

/**
 * Trait PhoneField
 *
 * @package Milebits\Eloquent\Filters\Concerns
 * @mixin Model
 */
trait PhoneField
{
    public static function bootPhoneField(): void
    {
        self::registerPhoneVerificationScope();
    }

    public static function registerPhoneVerificationScope(): void
    {
        $verificationEnabled = constVal(self::class, 'PHONE_VERIFICATION', false);
        if (!$verificationEnabled) $verificationEnabled = method_exists(self::class, 'phoneVerification') ? call_user_func([self::class, 'phoneVerification']) : $verificationEnabled;
        if ($verificationEnabled) self::addGlobalScope('phoneVerification', fn($builder) => $builder->verifiedPhone());
    }

    public function initializePhoneField(): void
    {
        $this->mergeFillable(Arr::wrap($this->getPhoneColumn()));
        $this->mergeFillable(Arr::wrap($this->getPhoneVerifiedAtColumn()));
        $this->mergeCasts([$this->getPhoneVerifiedAtColumn() => 'datetime']);
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
     *
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
     *
     * @return Builder
     */
    public function scopePhone(Builder $builder, string $phone, string $operator = '='): Builder
    {
        return $builder->where($this->decidePhoneColumn($builder), $operator, $phone);
    }

    /**
     * @param Builder $builder
     *
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
     *
     * @return Builder
     */
    public function scopePhoneLike(Builder $builder, string $phone): Builder
    {
        return $this->scopePhone($builder, Str::of($phone)->append('%'), 'like');
    }

    /**
     * @param Builder $builder
     * @param string $phone
     *
     * @return Builder
     */
    public function scopePhoneNotLike(Builder $builder, string $phone): Builder
    {
        return $this->scopePhone($builder, Str::of($phone)->append('%'), 'not like');
    }

    /**
     * @return string
     */
    public function getPhoneVerifiedAtColumn(): string
    {
        return constVal($this, 'PHONE_VERIFIED_AT_COLUMN', 'phone_verified_at');
    }

    /**
     * @return string
     */
    public function getQualifiedPhoneVerifiedAtColumn(): string
    {
        return $this->qualifyColumn($this->getPhoneVerifiedAtColumn());
    }

    /**
     * @param Builder $builder
     *
     * @return string
     */
    public function decidePhoneVerifiedAtColumn(Builder $builder): string
    {
        return count(property_exists($builder, 'joins') ? $builder->joins : []) > 0 ? $this->getQualifiedPhoneVerifiedAtColumn() : $this->getPhoneVerifiedAtColumn();
    }

    /**
     * @param Builder $builder
     * @param bool $verified
     *
     * @return Builder
     */
    public function scopeVerifiedPhone(Builder $builder, bool $verified = true): Builder
    {
        return $verified ? $builder->whereNotNull($this->decidePhoneVerifiedAtColumn($builder)) : $builder->whereNull($this->decidePhoneVerifiedAtColumn($builder));
    }

    /**
     * @param Builder $builder
     * @param bool $verified
     *
     * @return Builder
     */
    public function scopeUnverifiedPhone(Builder $builder, bool $verified = true): Builder
    {
        return $this->scopeVerifiedPhone($builder, !$verified);
    }

    /**
     * @param bool $verify
     *
     * @return $this
     */
    public function verifyPhone(bool $verify = true): self
    {
        $this->{$this->getPhoneVerifiedAtColumn()} = $verify ? now() : null;
        return $this;
    }

    /**
     * @param bool $unVerify
     *
     * @return $this
     */
    public function unVerifyPhone(bool $unVerify = true): self
    {
        return $this->verifyPhone(!$unVerify);
    }
}