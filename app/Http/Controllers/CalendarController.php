<?php

namespace App\Http\Controllers;

use App\Http\Requests\Calendar\CalendarCreate;
use App\Http\Requests\Calendar\CalendarUpdate;
use App\Http\Resources\Calendar\CalendarCollection;
use App\Http\Resources\Calendar\CalendarResource;
use App\Jobs\Calendars\CreateCalendar;
use App\Jobs\Calendars\DeleteCalendar;
use App\Jobs\Calendars\UpdateCalendar;
use App\Jobs\Slots\CreateSlot;
use App\Jobs\Slots\SyncSlots;
use App\Jobs\Slots\UpdateSlot;
use App\Models\Calendar;
use App\Repositories\CalendarsRepository;
use App\Repositories\Criteria\BelongsToProperty;
use App\Repositories\Criteria\ModelFilter;
use App\Repositories\Criteria\RequestWith;
use App\Repositories\PropertiesRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class CalendarController extends Controller
{
    /**
     * @var CalendarsRepository $calendar
     */
    private CalendarsRepository $calendar;

    /**
     * @var PropertiesRepository $property
     */
    private PropertiesRepository $property;

    /**
     * CalendarController constructor.
     *
     * @param CalendarsRepository $calendar
     * @param PropertiesRepository $property
     */
    public function __construct(CalendarsRepository $calendar, PropertiesRepository $property)
    {
        $this->calendar = $calendar;
        $this->property = $property;
    }

    /**
     * @param Request $request
     * @param int $propertyId
     * @return RedirectResponse
     */
    public function index(Request $request, int $propertyId)
    {
        return redirect()->route('properties.index');
    }

    /**
     * @param int $propertyId
     * @return Response
     */
    public function create(int $propertyId): Response
    {
        return Inertia::render('Calendars/Create', [
            'property_id' => $propertyId
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $propertyId
     * @param int $id
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(int $propertyId, int $id)
    {
        $calendar = $this->calendar->pushCriteria(new RequestWith())->findWhereOrFail(['id' => $id, 'property_id' => $propertyId]);
        $this->authorize('view', $calendar);

        return Inertia::render('Calendars/Show', [
            'calendar' => new CalendarResource($calendar)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CalendarCreate $request
     * @param int $propertyId
     * @return RedirectResponse
     */
    public function store(CalendarCreate $request, int $propertyId): RedirectResponse
    {
        $property = $this->property->findOrFail($propertyId);
        $this->authorize('create', [Calendar::class, $property]);

        $calendar = CreateCalendar::dispatchNow($request, $propertyId);

        if($request->has('slots')) {
            SyncSlots::dispatchNow($request->slots, $calendar);
        }

        return Redirect::route('properties.show', ['id' => $propertyId]);
    }

    /**
     * @param int $propertyId
     * @param int $id
     * @return Response
     */
    public function edit(int $propertyId, int $id): Response
    {
        $calendar = $this->calendar->findWhereOrFail(['id' => $id, 'property_id' => $propertyId]);

        return Inertia::render('Calendars/Edit', [
            'property_id' => $propertyId,
            'calendar' => $calendar,
            'currentSlots' => $calendar->slotsGroupedByDay()
        ]);
    }

    /**
     * @param CalendarUpdate $request
     * @param int $propertyId
     * @param int $id
     * @return RedirectResponse
     */
    public function update(CalendarUpdate $request, int $propertyId, int $id): RedirectResponse
    {
        $calendar = $this->calendar->findWhereOrFail(['id' => $id, 'property_id' => $propertyId]);
        $this->authorize('update', $calendar);

        UpdateCalendar::dispatchNow($request, $calendar);

        if($request->has('slots')) {
            SyncSlots::dispatchNow($request->slots, $calendar);
        }

        return Redirect::route('properties.show', ['id' => $propertyId]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $propertyId
     * @param int $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $propertyId, $id)
    {
        $calendar = $this->calendar->findWhereOrFail(['id' => $id, 'property_id' => $propertyId]);
        $this->authorize('delete', $calendar);

        DeleteCalendar::dispatchNow($calendar);

        return redirect()->back();
    }
}
