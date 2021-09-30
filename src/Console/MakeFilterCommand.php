<?php


namespace Milebits\Eloquent\Filters\Console;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFilterCommand extends Command
{
    protected ?string $stub = null;

    public function __parentConstruct()
    {
        parent::__construct();
    }

    /**
     * MakeFilterCommand constructor.
     */
    public function __construct()
    {
        $this->description = "Create a new eloquent model filter for requests.";
        $this->signature = "make:filter {filter}";

        $this->stub = $this->resolveFullyQualifiedPath(__DIR__ . "/../../stubs/ModelFilter.php.stub");

        parent::__construct();
    }

    /**
     * @param string $path
     * @return false|string
     */
    public function resolveFullyQualifiedPath(string $path): bool|string
    {
        return realpath(trim($path, '/'));
    }

    public function handle()
    {
        $filterName = $this->argument('filter');

        if (empty($filterName)) {
            $this->error("No filter name inserted");
            return;
        }

        File::ensureDirectoryExists(app_path('Filters'));

        $filterName = Str::of($filterName)->endsWith('Filter') ? $filterName : ($filterName . "Filter");

        if (!$this->copyStub($filterName)) {
            $this->alert("Cannot create Filter $filterName maybe it already exists inside your Filters folder ?");
            return;
        }

        $this->info("Model filter $filterName created successfully! enjoy :)");
        $this->info("Please consider supporting the open sourcing by doing a donation at opensource.milebits.com or os.milebits.com !");
    }

    /**
     * @param string $name
     * @return bool
     */
    public function copyStub(string $name): bool
    {
        if (file_exists(app_path("Filters/$name.php")))
            return false;

        $stubFile = Str::of(file_get_contents($this->stub));

        File::put(app_path("Filters/$name.php"), $stubFile->replace("FilterName", $name));

        return true;
    }
}