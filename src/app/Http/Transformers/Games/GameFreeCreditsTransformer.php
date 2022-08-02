<?php
namespace App\Http\Transformers\Games;

use App\Http\Transformers\BaseTransformer;
use App\Http\Transformers\Constants\ConstantTransformer;
use App\Http\Transformers\Users\UserTransformer;
use App\Models\Game;
use App\Models\GamePlayer;

class GameFreeCreditsTransformer extends BaseTransformer
{
    public function transform($gameWallet)
    {
        return [
            'name' => $gameWallet->game->name,
            'credit' => $gameWallet->credit,
        ];
    }
}
