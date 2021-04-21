<?php

namespace App\Http\Requests\Booking;

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
        return [
            'slot_id' =>
                [
                    'integer',
                    'exists:slots,id',
                    'required',
                ],
            'date' => 'required|date_format:Y-m-d|after:yesterday'
        ];
    }
}
