<?php

namespace App\Http\Requests\Payments\Red6six;

use App\Models\Currency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddCreditsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'required',
            'credits' => 'required|integer|gt:0'
        ];
    }
}
