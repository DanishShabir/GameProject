<?php
namespace App\Http\Transformers\HearAboutUs;

use App\Http\Transformers\BaseTransformer;
use App\Models\HearAboutUs;
use App\Models\User;

class HearAboutUsCountTransformer extends BaseTransformer
{
    public function transform(User $user)
    {
        return [
            'id' => $user->hearAboutUsPlatform->id,
            'name' => $user->hearAboutUsPlatform->name,
            'label' => $user->hearAboutUsPlatform->label,
            'count' => $user->total
        ];
    }
}
