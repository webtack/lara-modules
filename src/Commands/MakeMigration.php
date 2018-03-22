<?php

namespace Webtack\Modules\Commands;

use Illuminate\Console\Command;
use Webtack\Modules\Eloquent\Module;

class MakeMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:make:migration {name} {--module=} {--table=} {--create=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create migration from you {module}';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    $name = $this->argument('name');
	    $moduleName = $this->option('module');
	    
	    
	    if(empty($moduleName))
	        $moduleName = $this->ask('Please provide the name of the module');
	    
	    $dir = 'migrations';
	    $modulePath = Module::get($moduleName)->relativePath;
	    $source = $modulePath . DIRECTORY_SEPARATOR . $dir;
	    
	    if(!is_dir(base_path($source))) {
	    	mkdir(base_path($source), 0775, true);
	    }
	    
	    
	    $this->call('make:migration', [
	    	'name' => $name,
		    '--path' => $source,
		    '--table' => $this->option('table'),
		    '--create' => $this->option('create'),
	    ]);
    }
}
