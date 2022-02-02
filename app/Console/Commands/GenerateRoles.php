<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
class GenerateRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate roles';

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
     * @return int
     */
    public function handle()
    {

        Role::create(['name' => 'P']);
        Role::create(['name' => 'C']);
        Role::create(['name' => 'A']);
        return $this->info('roles created.');
    }
}
