<?php

namespace App\Providers;

use App\Handlers\Passes\CreatePassHandler;
use App\Handlers\Slots\CreateSlotHandler;
use App\Jobs\Slots\CreateSlot;
use App\Listeners\CreatePass;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Bus;

class BusServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Bus::map([
            CreatePass::class => CreatePassHandler::class,
            CreateSlot::class => CreateSlotHandler::class
        ]);
    }
}
