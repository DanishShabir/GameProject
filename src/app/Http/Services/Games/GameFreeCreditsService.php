<?php

namespace App\Http\Services\Games;

use App\Http\Services\BaseService;
use App\Models\Game;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class GameFreeCreditsService extends BaseService
{
    /**
     * @return Collection
     */
    public function getAll() : Collection
    {
        $user = auth()->user();
        return $user->gameWallets()
            ->whereHas('game', function (Builder $query) {
                $query->where('status', Game::STATUS_LIVE);
            })
            ->with('game')
            ->get();
    }
}
