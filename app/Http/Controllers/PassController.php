<?php

namespace App\Http\Controllers;

use App\Http\Resources\Pass\PassCollection;
use App\Http\Resources\Pass\PassResource;
use App\Models\Pass;
use App\Repositories\CalendarsRepository;
use App\Repositories\Criteria\BelongsToCalendar;
use App\Repositories\Criteria\ModelFilter;
use App\Repositories\Criteria\RequestWith;
use App\Repositories\PassRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PassController extends Controller
{
    /**
     * @var PassRepository $pass
     */
    private PassRepository $pass;

    /**
     * @var CalendarsRepository $calendar
     */
    private CalendarsRepository $calendar;

    /**
     * PassController constructor.
     *
     * @param PassRepository $pass
     * @param CalendarsRepository $calendar
     */
    public function __construct(PassRepository $pass, CalendarsRepository $calendar)
    {
        $this->pass = $pass;
        $this->calendar = $calendar;
    }

    /**
     * @param Request $request
     * @param int $calendarId
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request, int $calendarId): Response
    {
        $this->authorize('viewAny', Pass::class);

        $calendar = $this->calendar->findOrFail($calendarId);
        $this->authorize('view', $calendar);

        $limit = ($request->has('limit')) ? $request->get('limit'): 15;

        $passes = $this->pass
            ->pushCriteria(new BelongsToCalendar($calendar))
            ->pushCriteria(new RequestWith())
            ->pushCriteria(new ModelFilter())
            ->paginate($limit);

        return Inertia::render('Passes/Index', [
            'passes' => new PassCollection($passes)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param int $calendarId
     * @param int $id
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(int $calendarId, int $id)
    {
        $pass = $this->pass->pushCriteria(new RequestWith())->findWhereOrFail(['id' => $id, 'calendar_id' => $calendarId]);
        $this->authorize('view', $pass);

        return Inertia::render('Passes/Show', [
            'pass' => new PassResource($pass)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
