<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Artisan;
use App\suportclass\Setup;

class quickstart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quick:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quick start, migrate all tables, and get info';

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
        echo("Running setup... \n");
        echo("Migrate database... \n");
        Artisan::call('migrate:fresh');
        
        echo("Get info from remote database... \n");
        Setup::users();
        Setup::messages();
        Setup::follows();
        echo("Done, enjoy the app :) \n");
    }
}
