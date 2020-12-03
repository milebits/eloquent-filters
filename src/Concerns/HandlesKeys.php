<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Support\Str;
use Illuminate\Support\Stringable;

/**
 * Trait HandlesKeys
 * @package Milebits\Eloquent\Filters\Concerns
 */
trait HandlesKeys
{
    /**
     * @param array|null $attributes
     * @return array|null
     */
    public function keys(array $attributes = null): ?array
    {
        return collect($attributes ?? $this->extraKeyAttributes)->transform(function ($attribute) {
            return $this->key($attribute);
        })->toArray();
    }

    /**
     * @param string|null $attribute
     * @return string|null
     */
    public function key(string $attribute = null): ?string
    {
        $str = Str::of($this->defaultKey ?? $this->guessKeyName());
        if (!is_null($attribute))
            $str->append("_", $attribute);
        return $str;
    }

    /**
     * @return Stringable
     */
    public function guessKeyName(): string
    {
        return Str::of(class_basename($this))->beforeLast('Filter')->snake()->lower();
    }

    /**
     * @param string|null $keyAttribute
     * @param string|null $default
     * @return string|null
     */
    public function keyValue(string $keyAttribute = null, string $default = null): ?string
    {
        return $this->requestGet($this->key($keyAttribute), $default);
    }
}