<?php


namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use function constVal;

/**
 * Trait PasswordField
 * @package Milebits\Eloquent\Filters\Concerns
 * @mixin Model
 */
trait PasswordField
{
    public function initializePasswordField(): void
    {
        $this->mergeFillable(Arr::wrap($this->getPasswordColumn()));
        $this->makeHidden(Arr::wrap($this->getPasswordColumn()));
    }

    /**
     * @return string
     */
    public function getPasswordColumn(): string
    {
        return constVal($this, 'PASSWORD_COLUMN', 'password');
    }

    /**
     * @return string
     */
    public function getQualifiedPasswordColumn(): string
    {
        return $this->qualifyColumn($this->getPasswordColumn());
    }

    /**
     * @param int $length
     * @return string
     */
    public function generatePassword(int $length = 16): string
    {
        return Str::random($length);
    }

    /**
     * @param string $password
     * @return bool
     */
    public function checkPassword(string $password): bool
    {
        return Hash::check($this->{$this->getPasswordColumn()}, $password);
    }
}