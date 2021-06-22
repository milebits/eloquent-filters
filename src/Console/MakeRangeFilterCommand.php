<?php


namespace Milebits\Eloquent\Filters\Console;


/**
 * Class MakeRangeFilterCommand
 * @package Milebits\Eloquent\Filters\Console
 */
class MakeRangeFilterCommand extends MakeFilterCommand
{
    protected ?string $stub = null;

    /**
     * MakeFilterCommand constructor.
     */
    public function __construct()
    {
        $this->description = "Create a new eloquent model range filter for requests.";
        $this->signature = "make:filter-range {filter}";
        $this->stub = $this->resolveFullyQualifiedPath(__DIR__ . "/../../stubs/ModelRangeFilter.php.stub");
        parent::__construct();
    }
}