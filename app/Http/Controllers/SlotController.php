<?php

namespace App\Http\Controllers;

use App\Http\Requests\Slot\SlotCreate;
use App\Http\Resources\Slot\SlotCollection;
use App\Models\Calendar;
use App\Models\Slot;
use App\Repositories\Criteria\BelongsToCalendar;
use App\Repositories\Criteria\BelongsToUser;
use App\Repositories\Criteria\ModelFilter;
use App\Repositories\Criteria\RequestWith;
use App\Repositories\SlotsRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SlotController extends Controller
{
    /**
     * @var Slot $slot
     */
    private $slot;

    /**
     * SlotController constructor.
     *
     * @param SlotsRepository $slot
     */
    public function __construct(SlotsRepository $slot)
    {
        $this->slot = $slot;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Calendar $calendar
     * @return Response
     */
    public function index(Request $request, Calendar $calendar): Response
    {
        $slots = $this->slot
            ->pushCriteria(new BelongsToCalendar($calendar))
            ->pushCriteria(new BelongsToUser())
            ->pushCriteria(new RequestWith())
            ->pushCriteria(new ModelFilter())
            ->all();

        return Inertia::render('Slots/Index', [
            'date' => ($request->date && $request->date != '') ? $request->date : '',
            'slots' => new SlotCollection($slots)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param SlotCreate $request
     * @return RedirectResponse
     */
    public function store(SlotCreate $request): RedirectResponse
    {
        $this->slot->create($request->validated());

        return redirect()->back()->withFlash('Slot Created Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $this->slot->findOrFail($id)->delete();

        return redirect()->back()->withFlash('Slot Deleted Successfully.');
    }
}
