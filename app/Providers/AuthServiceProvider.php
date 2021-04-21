<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\Calendar;
use App\Models\Pass;
use App\Models\Property;
use App\Models\Slot;
use App\Models\Team;
use App\Models\UserProperty;
use App\Policies\BookingPolicy;
use App\Policies\CalendarPolicy;
use App\Policies\PassPolicy;
use App\Policies\PropertyPolicy;
use App\Policies\SlotPolicy;
use App\Policies\TeamPolicy;
use App\Policies\UserPropertyPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Booking::class => BookingPolicy::class,
        Calendar::class => CalendarPolicy::class,
        Pass::class => PassPolicy::class,
        Property::class => PropertyPolicy::class,
        UserProperty::class => UserPropertyPolicy::class,
        Slot::class => SlotPolicy::class,
        Team::class => TeamPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
