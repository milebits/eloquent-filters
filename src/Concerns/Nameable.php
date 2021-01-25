<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Database\Eloquent\Model;
use function Milebits\Eloquent\Filters\Helpers\constVal;

/**
 * Trait Nameable
 * @package Milebits\Eloquent\Filters\Concerns
 * @mixin Model
 */
trait Nameable
{
    /**
     * Boots the Nameable trait.
     *
     * @return void
     */
    public static function bootNameable(): void
    {
        static::addGlobalScope(new NameScope());
    }

    /**
     * Initializes the Nameable trait.
     *
     * @return void
     */
    public function initializeNameable(): void
    {
        $this->fillable = array_merge($this->fillable, [$this->getNameColumn()]);
    }

    /**
     * Gets the Name column name.
     *
     * @return string
     */
    public function getNameColumn()
    {
        return constVal($this, 'NAME_COLUMN', 'name');
    }

    /**
     * Qualifies the Name column name.
     *
     * @return string
     */
    public function getQualifiedNameColumn()
    {
        return $this->qualifyColumn($this->getNameColumn());
    }
}
