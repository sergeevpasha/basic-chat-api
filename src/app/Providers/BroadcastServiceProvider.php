<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Configure broadcasting for the application.
     */
    public function configure(): void
    {
        Broadcast::routes();

        require base_path('routes/channels.php');
    }
}
