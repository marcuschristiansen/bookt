<?php

namespace App\Http\Controllers;

use App\Http\Requests\Calendar\CalendarUpdate;
use App\Http\Resources\Calendar\CalendarCollection;
use App\Http\Resources\Calendar\CalendarResource;
use App\Models\Calendar;
use App\Repositories\CalendarsRepository;
use App\Repositories\Criteria\BelongsToTeam;
use App\Repositories\Criteria\BelongsToUser;
use App\Repositories\Criteria\ModelFilter;
use App\Repositories\Criteria\RequestWith;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CalendarController extends Controller
{
    /**
     * @var Calendar $calendar
     */
    private $calendar;

    /**
     * CalendarController constructor.
     *
     * @param CalendarsRepository $calendar
     */
    public function __construct(CalendarsRepository $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $limit = ($request->has('limit')) ? $request->get('limit') : 15;

        $calendars = $this->calendar
            ->pushCriteria(new BelongsToTeam())
            ->pushCriteria(new RequestWith())
            ->pushCriteria(new ModelFilter())
            ->orderBy('name')
            ->paginate($limit);

        return Inertia::render('Calendars/Index', [
            'calendars' => new CalendarCollection($calendars)
        ]);
    }

    /**
     * @param int $id
     * @return Response
     */
    public function edit(int $id): Response
    {
        $calendar = $this->calendar->findOrFail($id);

        return Inertia::render('Calendars/Edit', [
            'calendar' => $calendar,
            'slots' => $calendar->slots
        ]);
    }

    /**
     * @param CalendarUpdate $request
     * @param int $id
     * @return RedirectResponse
     */
    public function update(CalendarUpdate $request, int $id): RedirectResponse
    {
        $calendar = $this->calendar->findOrFail($id);
        $calendar->update($request->validated());

        return redirect()->back()->with('message', 'Calendar Updated Successfully.');
    }
}
