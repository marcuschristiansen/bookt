<?php

namespace App\Http\Controllers;

use App\Http\Requests\Slot\SlotCreate;
use App\Http\Requests\Slot\SlotUpdate;
use App\Http\Resources\Slot\SlotCollection;
use App\Http\Resources\Slot\SlotResource;
use App\Jobs\Slots\CreateSlot;
use App\Jobs\Slots\DeleteSlot;
use App\Jobs\Slots\UpdateSlot;
use App\Models\Slot;
use App\Repositories\CalendarsRepository;
use App\Repositories\Criteria\BelongsToCalendar;
use App\Repositories\Criteria\ModelFilter;
use App\Repositories\Criteria\RequestWith;
use App\Repositories\SlotsRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class SlotController extends Controller
{
    /**
     * @var SlotsRepository $slot
     */
    private SlotsRepository $slot;

    /**
     * @var CalendarsRepository $calendar
     */
    private CalendarsRepository $calendar;

    /**
     * SlotController constructor.
     *
     * @param SlotsRepository $slot
     * @param CalendarsRepository $calendar
     */
    public function __construct(SlotsRepository $slot, CalendarsRepository $calendar)
    {
        $this->slot = $slot;
        $this->calendar = $calendar;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param int $calendarId
     * @return Response
     */
    public function index(Request $request, int $calendarId): Response
    {
        $this->authorize('viewAny', Slot::class);

        $calendar = $this->calendar->findOrFail($calendarId);
        $this->authorize('view', $calendar);

        $limit = ($request->has('limit')) ? $request->get('limit'): 15;

        $slots = $this->slot
            ->pushCriteria(new BelongsToCalendar($calendar))
            ->pushCriteria(new RequestWith())
            ->pushCriteria(new ModelFilter())
            ->paginate($limit);

        return Inertia::render('Slots/Index', [
            'slots' => new SlotCollection($slots)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $calendarId
     * @param int $id
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(int $calendarId, int $id)
    {
        $slot = $this->slot->pushCriteria(new RequestWith())->findWhereOrFail(['id' => $id, 'calendar_id' => $calendarId]);
        $this->authorize('view', $slot);

        return Inertia::render('Slots/Show', [
            'slot' => new SlotResource($slot)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SlotCreate $request
     * @param int $calendarId
     * @return RedirectResponse
     */
    public function store(SlotCreate $request, int $calendarId): RedirectResponse
    {
        /**
         * NOT NEEDED RIGHT NOW
         */
//        $calendar = $this->calendar->findOrFail($calendarId);
//        $this->authorize('create', [Slot::class, $calendar]);
//
//        CreateSlot::dispatchNow($request, $calendarId);
//
//        return Redirect::route('slots.index', ['calendarId' => $calendarId]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function edit(int $id): Response
    {
        $slot = $this->slot->findOrFail($id);

        return Inertia::render('Slots/Edit', [
            'slot' => $slot
        ]);
    }

    /**
     * @param SlotUpdate $request
     * @param int $calendarId
     * @param int $id
     * @return RedirectResponse
     */
    public function update(SlotUpdate $request, int $calendarId, int $id): RedirectResponse
    {
        $slot = $this->slot->findWhereOrFail(['id' => $id, 'calendar_id' => $calendarId]);
        $this->authorize('update', $slot);

        UpdateSlot::dispatchNow($request, $slot);

        return Redirect::route('slots.index', ['calendarId' => $calendarId]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $calendarId
     * @param int $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(int $calendarId, $id)
    {
        $slot = $this->slot->findWhereOrFail(['id' => $id, 'calendar_id' => $calendarId]);
        $this->authorize('delete', $slot);

        DeleteSlot::dispatchNow($slot);

        return Redirect::route('slots.index', ['calendarId' => $calendarId]);
    }
}
