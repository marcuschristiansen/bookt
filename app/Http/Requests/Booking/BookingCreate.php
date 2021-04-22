<?php

namespace App\Http\Requests\Booking;

use App\Models\Booking;
use App\Models\Slot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookingCreate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $slot = Slot::findOrFail(request()->slot_id);
        // Count the amount of times this slot has already been used.
        $slotUsageCount = Booking::where(['date' => request()->date])->whereHas('slots', function(Builder $query) {
            $query->where('slots.id', request()->slot_id);
        })->get();
        $userAlreadyBooked = $slotUsageCount->contains(fn($value, $key) => $value->user_id === auth()->user()->getKey());

        return [
            'slot_id' =>
                [
                    'integer',
                    'exists:slots,id',
                    'required',
                    fn ($attribute, $value, $fail) => $slotUsageCount->count() < $slot->max_bookings ?: $fail('The booking limit for this slot has been reached'),
                    fn ($attribute, $value, $fail) => !$userAlreadyBooked ?: $fail('You have already made a booking for this slot.')
                ],
            'date' => 'required|date_format:Y-m-d|after:yesterday'
        ];
    }
}
