<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
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
            'note' => 'required|max:500',
            'do_from' => 'date',
            'do_until' => 'date|after_or_equal:do_from',
            'initiator' => 'exists:users,id',
            'doer' => 'exists:users,id',
            'status' => 'in:open,progress,closed',
        ];
    }
}
