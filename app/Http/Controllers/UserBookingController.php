<?php

namespace App\Http\Controllers;

use App\Http\Requests\Booking\BookingCreate;
use App\Http\Resources\Booking\BookingCollection;
use App\Http\Resources\BookingSlot\BookingSlotCollection;
use App\Models\Booking;
use App\Models\Calendar;
use App\Models\Property;
use App\Repositories\BookingSlotsRepository;
use App\Repositories\BookingsRepository;
use App\Repositories\Criteria\BelongsToBookings;
use App\Repositories\Criteria\BelongsToUser;
use App\Repositories\Criteria\isPropertyMemberOf;
use App\Repositories\Criteria\RequestWith;
use App\Repositories\PropertiesRepository;
use App\Repositories\SlotsRepository;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserBookingController extends Controller
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
     * @var PropertiesRepository $property
     */
    private PropertiesRepository $property;

    /**
     * @var SlotsRepository $slot
     */
    private SlotsRepository $slot;

    /**
     * UserBookingController constructor.
     * @param BookingsRepository $booking
     * @param BookingSlotsRepository $bookingSlot
     * @param PropertiesRepository $property
     * @param SlotsRepository $slot
     */
    public function __construct(BookingsRepository $booking, BookingSlotsRepository $bookingSlot, PropertiesRepository $property, SlotsRepository $slot)
    {
        $this->booking = $booking;
        $this->bookingSlot = $bookingSlot;
        $this->property = $property;
        $this->slot = $slot;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param int $userId
     * @return Response
     */
    public function index(Request $request, int $userId): Response
    {
        $this->authorize('viewAny', [Booking::class, $userId]);

        if(!$request->has('date')) {
            request()->request->add(['date' => Carbon::now()->format('Y-m-d')]);
        }

        $bookings = $this->booking
            ->pushCriteria(new BelongsToUser($userId))
            ->pushCriteria(new RequestWith())
            ->with(['property', 'slots'])
            ->all();

        $bookingSlots = $this->bookingSlot
            ->pushCriteria(new BelongsToBookings($bookings))
            ->with(['booking', 'slot', 'booking.property', 'booking.user', 'slot.calendar'])
            ->all();

        return Inertia::render('Users/Bookings/Index', [
            'bookings' => new BookingCollection($bookings),
            'bookingSlots' => new BookingSlotCollection($bookingSlots)
        ]);
    }

    /**
     * @param Request $request
     * @param int $userId
     * @return Response
     */
    public function create(Request $request, int $userId): Response
    {
        $properties = $this->property->pushCriteria(new isPropertyMemberOf($userId))->all();
        return Inertia::render('Users/Bookings/Create', [
            'properties' => $properties->map(function(Property $property) {
                return [
                    'id' => $property['id'],
                    'label' => $property['name'],
                    'calendars' => $property->calendars->map(function(Calendar $calendar) {
                        return [
                            'id' => $calendar['id'],
                            'label' => $calendar['name'],
                            'slots' => $calendar->slots
                        ];
                    })
                ];
            }),
            'property' => $request->has('property') ? (int)$request->property : $properties->first(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookingCreate $request
     * @param int $userId
     * @return RedirectResponse
     */
    public function store(BookingCreate $request, int $userId): RedirectResponse
    {
        $slot = $this->slot->findOrFail($request->slot_id);
        $formRequest = array_merge($request->only('date'), ['user_id' => $userId, 'property_id' => $slot->calendar->property->getKey()]);
        $booking = $this->booking->create($formRequest);
        $booking->slots()->sync([$request->slot_id]);

        return redirect()->route('user-bookings.index', auth()->user()->getKey());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request, int $userId, int $id)
    {
        $booking = $this->booking->findOrFail($id);
        $this->authorize('delete', $booking);

        $booking->slots()->detach($request->slot_id);
        if($booking->slots()->count() === 0) {
            $booking->delete();
        }

        return redirect()->back();
    }
}
