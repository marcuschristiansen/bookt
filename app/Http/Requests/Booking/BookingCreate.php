<?php

namespace App\Http\Requests\Booking;

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
        $user = auth()->user();

        return [
            'slot_id' =>
                [
                    'integer',
                    'exists:slots,id',
                    'required',
                    Rule::unique('bookings')->where(function($query) use ($user) {
                        $query->where('user_id', $user->getKey())->where('slot_id', $this->slot_id);
                    })
                ],
            'date' => 'required|date_format:Y-m-d|after:yesterday'
        ];
    }
}
