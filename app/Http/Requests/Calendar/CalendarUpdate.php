<?php

namespace App\Http\Requests\Calendar;

use Illuminate\Foundation\Http\FormRequest;

class CalendarUpdate extends FormRequest
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
            'name' => 'required|string',
            'slots' => 'nullable|array',
            'slots.*.items' => 'nullable|array',
            'slots.*.items.*.start_time' => 'required|date_format:H:i',
            'slots.*.items.*.end_time' => 'required|date_format:H:i',
            'slots.*.items.*.max_bookings' => 'required|min:0|integer',
            'slots.*.items.*.cost' => 'nullable|min:0|numeric',
        ];
    }

    /**
     * Custom validation messages
     *
     * @return string[]
     */
    public function messages()
    {
        return [
            'slots.*.items.*.*.required' => 'This field is required',
            'slots.*.items.*.*.date_format' => 'Incorrect time format. Eg. 09:00',
        ];
    }
}
