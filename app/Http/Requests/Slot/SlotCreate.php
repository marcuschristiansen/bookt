<?php

namespace App\Http\Requests\Slot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SlotCreate extends FormRequest
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
            'calendar_id' => ['exists:calendars,id'],
            'day_id' => ['required', 'integer', Rule::in(range(1,7))],
            'max_bookings' => ['required', 'integer', 'min:0'],
            'start_time' => ['required', 'string', 'date_format:H:i'],
            'end_time' => ['required', 'string', 'date_format:H:i'],
        ];
    }
}
