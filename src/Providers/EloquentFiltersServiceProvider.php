<?php

namespace Milebits\Eloquent\Filters\Providers;

use Illuminate\Support\ServiceProvider;
use Milebits\Eloquent\Filters\Console\MakeFilterCommand;
use Milebits\Eloquent\Filters\Console\MakeRangeFilterCommand;

/**
 * Class EloquentFiltersServiceProvider.
 */
class EloquentFiltersServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (!$this->app->runningInConsole()) {
            return;
        }
        $this->commands([MakeFilterCommand::class, MakeRangeFilterCommand::class]);
    }
}
