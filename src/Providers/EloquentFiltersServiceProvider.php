<?php

namespace Milebits\Eloquent\Filters\Providers;

use Illuminate\Support\ServiceProvider;
use Milebits\Eloquent\Filters\Console\MakeFilterCommand;

/**
 * Class EloquentFiltersServiceProvider
 * @package Milebits\Eloquent\Filters\Providers
 */
class EloquentFiltersServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole())
            $this->commands([MakeFilterCommand::class]);
    }
}