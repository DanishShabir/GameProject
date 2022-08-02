<?php

namespace App\Http\Requests\Games;

use Illuminate\Foundation\Http\FormRequest;

class CreateGameServerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'user_auth_token' => 'required|string',
            'game_id' => 'required|integer|exists:games,id',
            'user_id' => 'required|integer|exists:users,id'
        ];
    }
}
