<?php

namespace Milebits\Eloquent\Filters\Concerns;

use Illuminate\Http\Request;

/**
 * Trait HandlesRequests
 * @package Milebits\Eloquent\Filters\Concerns
 */
trait HandlesRequests
{
    /**
     * @param array $keys
     * @return bool
     */
    public function requestHasAll(array $keys): bool
    {
        $keys = collect($keys);
        $count = $keys->count();
        return $keys->reject(function ($item) {
                return !$this->requestHas($item);
            })->count() == $count;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function requestHas(string $key): bool
    {
        return $this->request()->has($key);
    }

    /**
     * @return Request
     */
    public function request(): Request
    {
        return request();
    }

    /**
     * @param array $keys
     * @return bool
     */
    public function requestHasAtLeastOneOf(array $keys): bool
    {
        return collect($keys)->reject(function ($item) {
            return !$this->requestHas($item);
        })->isNotEmpty();
    }

    /**
     * @param string $key
     * @param $default
     * @return string|null
     */
    public function requestGet(string $key, string $default = null): ?string
    {
        return $this->request()->get($key, $default);
    }
}