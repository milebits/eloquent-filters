<?php

namespace Milebits\Eloquent\Filters\Console;

class MakeRangeFilterCommand extends MakeFilterCommand
{
    protected ?string $stub = __DIR__.'/../../stubs/ModelRangeFilter.php.stub';

    protected $description = 'Create a new eloquent model range filter for requests.';

    protected $signature = 'make:filter-range {filter}';
}
