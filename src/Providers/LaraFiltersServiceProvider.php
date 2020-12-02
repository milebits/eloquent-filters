<?php

namespace Milebits\Eloquent\Filters\Providers;

use Illuminate\Support\ServiceProvider;
use Milebits\Eloquent\Filters\Console\MakeFilterCommand;

class LaraFiltersServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            MakeFilterCommand::class,
        ]);
    }

    public function register()
    {

    }
}