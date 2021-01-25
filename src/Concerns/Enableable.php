<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Model;
use function Milebits\Eloquent\Filters\Helpers\constVal;

/**
 * Trait Enableable
 * @package Milebits\Eloquent\Filters\Concerns
 *
 * @mixin Model
 */
trait Enableable
{
    /**
     * @return void
     */
    public static function bootEnableable(): void
    {
        static::addGlobalScope(new EnableableScope());
    }

    /**
     * @return void
     */
    public function initializeEnableable(): void
    {
        $this->fillable = array_merge($this->fillable, [$this->getEnabledColumn()]);
    }

    /**
     * @return string
     */
    public function getEnabledColumn(): string
    {
        return constVal($this, 'ENABLED_COLUMN', 'enabled');
    }

    /**
     * @return string
     */
    public function getQualifiedEnabledColumn(): string
    {
        return $this->qualifyColumn($this->getEnabledColumn());
    }

    /**
     * @return $this
     */
    public function enable(): self
    {
        $this->{$this->getEnabledColumn()} = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function disable(): self
    {
        $this->{$this->getEnabledColumn()} = false;
        return $this;
    }

    /**
     * @return bool
     */
    public function getEnableableScopeActivationValue(): bool
    {
        return constVal($this, 'EnableableScopeActivationValue', true);
    }
}
