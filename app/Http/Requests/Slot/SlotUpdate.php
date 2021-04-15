<?php

namespace App\Http\Requests\Slot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SlotUpdate extends FormRequest
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
            'max_bookings' => ['nullable', 'integer', 'min:0'],
            'start_time' => ['required', 'string', 'date_format:H:i'],
            'end_time' => ['required', 'string', 'date_format:H:i'],
            'cost' => ['nullable', 'min:0'],
            'day_id' => ['required', Rule::in([0,1,2,3,4,5,6]),]
        ];
    }
}
