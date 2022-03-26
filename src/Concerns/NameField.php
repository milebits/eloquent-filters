<?php

namespace Milebits\Eloquent\Filters\Concerns;

use function constVal;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait NameField.
 *
 * @mixin Model
 */
trait NameField
{
    /**
     * Boots the NameField trait.
     *
     * @return void
     */
    public static function bootNameField(): void
    {
        static::addGlobalScope(new NameScope());
    }

    /**
     * Initializes the NameField trait.
     *
     * @return void
     */
    public function initializeNameField(): void
    {
        $this->fillable = array_merge($this->fillable, [$this->getNameColumn()]);
    }

    /**
     * Gets the Name column name.
     *
     * @return string
     */
    public function getNameColumn(): string
    {
        return constVal($this, 'NAME_COLUMN', 'name');
    }

    /**
     * Qualifies the Name column name.
     *
     * @return string
     */
    public function getQualifiedNameColumn(): string
    {
        return $this->qualifyColumn($this->getNameColumn());
    }
}
