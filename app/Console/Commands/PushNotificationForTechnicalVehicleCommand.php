<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\PushNotificationVehicleControlTrait;
class PushNotificationForTechnicalVehicleCommand extends Command
{
    use PushNotificationVehicleControlTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vehicle:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'push notification command for technical vehicle';

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
        $this->index();
        return $this->info('work');
    }
}
