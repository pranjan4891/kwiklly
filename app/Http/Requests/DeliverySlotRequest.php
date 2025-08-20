<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliverySlotRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check() || auth('vendor')->check();
    }

    public function rules()
    {
        return [
            'date'            => 'nullable|date',
            'time_range'      => 'nullable|string|max:255',
            'is_express'      => 'required|in:0,1,3',
            'express_charge'  => 'required_if:is_express,1,3|nullable|integer|min:0',

            // ✅ Single default_minutes for both Express & Default Express
            'default_minutes' => 'required_if:is_express,1,3|nullable|integer|min:1|max:300',

            'start_time'      => 'nullable|date_format:H:i',
            'end_time'        => 'nullable|date_format:H:i|after:start_time',
        ];
    }

    public function prepareForValidation()
    {
        if ($this->has('is_express')) {
            $this->merge([
                'is_express' => (int) $this->input('is_express'),
            ]);
        }
    }
}
