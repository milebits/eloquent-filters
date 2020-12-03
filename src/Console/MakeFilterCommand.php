<?php


namespace Milebits\Eloquent\Filters\Console;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeFilterCommand extends Command
{
    protected ?string $stub = null;

    /**
     * MakeFilterCommand constructor.
     */
    public function __construct()
    {
        $this->description = "Make new eloquent model filter for requests.";
        $this->signature = "milebits:make:filter {filter}";

        $this->stub = $this->resolveFullyQualifiedPath(__DIR__ . "/../../stubs/ModelFilter.php.stub");

        parent::__construct();
    }

    /**
     * @param string $path
     * @return false|string
     */
    public function resolveFullyQualifiedPath(string $path)
    {
        return realpath(trim($path, '/'));
    }

    public function handle()
    {
        $filterName = $this->argument('filter');

        File::ensureDirectoryExists(app_path('Filters'));

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