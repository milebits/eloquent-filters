<?php

namespace Milebits\Eloquent\Filters\Concerns;

use function constVal;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait EnableField.
 *
 * @mixin Model
 */
trait EnableField
{
    /**
     * @return void
     */
    public static function bootEnableField(): void
    {
        static::addGlobalScope(new EnabledScope());
    }

    /**
     * @return void
     */
    public function initializeEnableField(): void
    {
        $this->fillable = array_merge($this->fillable, [$this->getEnabledColumn()]);
    }

    /**
     * @return string
     */
    public function getEnabledColumn(): string
    {
        return constVal($this, 'ENABLED_COLUMN', 'enable');
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
    public function getEnableFieldScopeActivationValue(): bool
    {
        return constVal($this, 'ENABLE_DEFAULT_VALUE', true);
    }
}
