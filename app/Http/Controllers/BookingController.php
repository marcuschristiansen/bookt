<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\BookingCreate;
use App\Http\Resources\Booking\BookingCollection;
use App\Http\Resources\Booking\BookingResource;
use App\Http\Resources\Slot\SlotCollection;
use App\Models\Booking;
use App\Models\Calendar;
use App\Models\Slot;
use App\Repositories\BookingsRepository;
use App\Repositories\CalendarsRepository;
use App\Repositories\Criteria\AlreadyBooked;
use App\Repositories\Criteria\BookingsByRole;
use App\Repositories\Criteria\ModelFilter;
use App\Repositories\Criteria\RequestWith;
use App\Repositories\SlotsRepository;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookingController extends Controller
{
    /**
     * @var Booking $booking
     */
    private $booking;

    /**
     * @var Slot $slot
     */
    private $slot;

    /**
     * @var Calendar $calendar
     */
    private $calendar;

    /**
     * BookingController constructor.
     * @param BookingsRepository $booking
     * @param SlotsRepository $slot
     * @param CalendarsRepository $calendar
     */
    public function __construct(BookingsRepository $booking, SlotsRepository $slot, CalendarsRepository $calendar)
    {
        $this->booking = $booking;
        $this->slot = $slot;
        $this->calendar = $calendar;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $limit = ($request->has('limit')) ? $request->get('limit') : 20;

        if(!$request->user()->currentTeam) {
            return Inertia::render('Bookings/Index');
        }

        $bookings = $this->booking
            ->pushCriteria(new BookingsByRole())
            ->pushCriteria(new RequestWith())
            ->pushCriteria(new ModelFilter())
            ->orderBy('date')
            ->paginate($limit);

        return Inertia::render('Bookings/Index', [
            'date' => $request->has('date') ? Carbon::create($request->date)->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
            'calendar' => $request->has('calendar') ? (int)$request->calendar : '',
            'bookings' => new BookingCollection($bookings)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $limit = ($request->has('limit')) ? $request->get('limit') : 20;

        $calendar = $request->calendar;
        if(!$calendar) {
            $calendar = auth()->user()->currentTeam->calendars->first()->getKey();
            $request->request->add(['calendar' => $calendar]);
        }

        $date = $request->date;
        if(!$date) {
            $date = Carbon::now()->format('Y-m-d');
            $request->request->add(['date' => $date]);
        }

        $slots = $this->slot
            ->pushCriteria(new RequestWith())
            ->pushCriteria(new AlreadyBooked())
            ->pushCriteria(new ModelFilter())
            ->orderBy('start_time')
            ->paginate($limit);

        // filter out slots that have reached their booking limit
        $filteredSlots = $slots->filter(function($slot) {
            return $slot->bookings->count() < $slot->max_bookings;
        });

        return Inertia::render('Bookings/Create', [
            'slots' => new SlotCollection($filteredSlots),
            'calendar' => (int)$calendar,
            'date' => Carbon::create($date)->format('Y-m-d')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookingCreate $request
     * @return RedirectResponse
     */
    public function store(BookingCreate $request): RedirectResponse
    {
        $user = auth()->user();
        $formRequest = array_merge($request->validated(), ['user_id' => $user->getKey(), 'team_id' => $user->currentTeam->getKey()]);
        $this->booking->create($formRequest);

        return redirect()->route('bookings.index')->with('message', 'Booking Created Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        $this->booking->findOrFail($id)->delete();

        return redirect()->back()->with('message', 'Booking Deleted Successfully.');
    }
}
