<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\BookingCreate;
use App\Http\Resources\Booking\BookingCollection;
use App\Http\Resources\Booking\BookingResource;
use App\Http\Resources\BookingSlot\BookingSlotCollection;
use App\Http\Resources\Slot\SlotCollection;
use App\Repositories\BookingSlotsRepository;
use App\Repositories\BookingsRepository;
use App\Repositories\CalendarsRepository;
use App\Repositories\Criteria\AlreadyBooked;
use App\Repositories\Criteria\BelongsToBookings;
use App\Repositories\Criteria\BookingsByTeam;
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
     * @var BookingsRepository $booking
     */
    private BookingsRepository $booking;

    /**
     * @var BookingSlotsRepository $bookingSlot
     */
    private BookingSlotsRepository $bookingSlot;

    /**
     * @var SlotsRepository $slot
     */
    private SlotsRepository $slot;

    /**
     * @var CalendarsRepository $calendar
     */
    private CalendarsRepository $calendar;

    /**
     * UserBookingController constructor.
     *
     * @param BookingsRepository $booking
     * @param BookingSlotsRepository $bookingSlot
     * @param SlotsRepository $slot
     * @param CalendarsRepository $calendar
     */
    public function __construct(BookingsRepository $booking, BookingSlotsRepository $bookingSlot, SlotsRepository $slot, CalendarsRepository $calendar)
    {
        $this->booking = $booking;
        $this->bookingSlot = $bookingSlot;
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
        if(!$request->has('date')) {
            request()->request->add(['date' => Carbon::now()->format('Y-m-d')]);
        }

        $bookings = $this->booking
            ->pushCriteria(new BookingsByTeam())
            ->pushCriteria(new RequestWith())
            ->pushCriteria(new ModelFilter())
            ->all();

        $bookingSlots = $this->bookingSlot
            ->pushCriteria(new BelongsToBookings($bookings))
            ->with(['booking', 'slot', 'booking.property', 'booking.user', 'slot.calendar'])
            ->all();

        return Inertia::render('Bookings/Index', [
            'date' => $request->has('date') ? Carbon::create($request->date)->format('Y-m-d') : Carbon::now()->format('Y-m-d'),
            'properties' => auth()->user()->currentTeam->properties->map(function($item, $index) {
                return [
                    'id' => $item['id'],
                    'label' => $item['name']
                ];
            }),
            'property' => $request->has('property') ? (int)$request->property : auth()->user()->currentTeam->properties->first()->getKey(),
            'bookings' => new BookingCollection($bookings),
            'bookingSlots' => new BookingSlotCollection($bookingSlots)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id): Response
    {
        $booking = $this->booking->pushCriteria(new RequestWith())->with(['user', 'slots', 'slots.calendar', 'property'])->find($id);
        $this->authorize('view', $booking);

        return Inertia::render('Bookings/Show', [
            'booking' => new BookingResource($booking)
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

    /**
     * Remove a specific booking slot of a booking
     *
     * @param int $id
     */
    public function destroyBookingSlot(int $id)
    {
        $this->bookingSlot->find($id)->delete();

        return redirect()->route('bookings.index');
    }
}
