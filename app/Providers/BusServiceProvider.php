<?php

namespace App\Providers;

use App\Handlers\Calendars\CreateCalendarHandler;
use App\Handlers\Calendars\DeleteCalendarHandler;
use App\Handlers\Calendars\UpdateCalendarHandler;
use App\Handlers\Properties\CreatePropertyHandler;
use App\Handlers\Properties\DeletePropertyHandler;
use App\Handlers\Properties\UpdatePropertyHandler;
use App\Handlers\UserProperties\CreateUserPropertyHandler;
use App\Handlers\UserProperties\DeleteUserPropertyHandler;
use App\Handlers\Slots\CreateSlotHandler;
use App\Handlers\Slots\DeleteSlotHandler;
use App\Handlers\Slots\SyncSlotsHandler;
use App\Handlers\Slots\UpdateSlotHandler;
use App\Jobs\Calendars\CreateCalendar;
use App\Jobs\Calendars\DeleteCalendar;
use App\Jobs\Calendars\UpdateCalendar;
use App\Jobs\Properties\CreateProperty;
use App\Jobs\Properties\DeleteProperty;
use App\Jobs\Properties\UpdateProperty;
use App\Jobs\UserProperties\CreateUserProperty;
use App\Jobs\UserProperties\DeleteUserProperty;
use App\Jobs\Slots\CreateSlot;
use App\Jobs\Slots\DeleteSlot;
use App\Jobs\Slots\SyncSlots;
use App\Jobs\Slots\UpdateSlot;
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
            CreateProperty::class => CreatePropertyHandler::class,
            UpdateProperty::class => UpdatePropertyHandler::class,
            DeleteProperty::class => DeletePropertyHandler::class,
            CreateCalendar::class => CreateCalendarHandler::class,
            UpdateCalendar::class => UpdateCalendarHandler::class,
            DeleteCalendar::class => DeleteCalendarHandler::class,
            CreateSlot::class => CreateSlotHandler::class,
            UpdateSlot::class => UpdateSlotHandler::class,
            DeleteSlot::class => DeleteSlotHandler::class,
            SyncSlots::class => SyncSlotsHandler::class,
            CreateUserProperty::class => CreateUserPropertyHandler::class,
            DeleteUserProperty::class => DeleteUserPropertyHandler::class
        ]);
    }
}
