<?php
namespace App\Http\Transformers\Games;

use App\Http\Transformers\BaseTransformer;
use App\Http\Transformers\Constants\ConstantTransformer;
use App\Http\Transformers\Maps\MapAdminTransformer;
use App\Http\Transformers\Games\GameWinnerEarningTransformer;
use App\Models\Game;

class GameUserAdminTransformer extends BaseTransformer
{
    protected $defaultIncludes = [
        'status'
    ];
 
    protected $availableIncludes = [
        'players', 'map', 'earnings', 'historical_earnings'
    ];

    public function transform(Game $game)
    {
        return [
            'id' => $game->id,
            'name' => $game->name,
            'entry_fee' => $game->entry_fee,
            'jackpot_value' => $game->jackpot_value,
            'is_extended' => (int) $game->is_extended,
            'start_date' => $game->start_date->format('Y-m-d\TH:i'),
            'end_date' => $game->end_date->format('Y-m-d\TH:i'),
            'days_remaining' => $game->days_remaining,
            'amount_of_balls_to_fire' => $game->amount_of_balls_to_fire,
            'total_attempts' => $game->total_attempts,
            'admin_fee_percentage' => $game->admin_fee_percentage,
            'number_of_winners' => $game->number_of_winners,
            'pot_value' => $game->pot_value,
            'game_ext_days' => $game->game_ext_days,
            'pivot' => $game->pivot
        ];
    }

    public function includeStatus(Game $game)
    {
        $item = [
            'id' => $game->status,
            'name' => data_get(Game::statuses(), $game->status)
        ];

        return $this->item($item, new ConstantTransformer);
    }

    public function includeMap(Game $game)
    {
        return $this->item($game->maps()->first()->map, new MapAdminTransformer);
    }

    public function includePlayers(Game $game)
    {
        $players = $game->players()
            ->orderBy('highest_score', 'DESC')
            ->orderBy('shortest_duration', 'ASC')
            ->get();
        return $this->collection($players, new GamePlayerAdminTransformer);
    }

    public function includeEarnings(Game $game)
    {
        $earnings = $game->earnings()->get();
        return $this->collection($earnings, new GameWinnerEarningTransformer);
    }

    public function includeHistoricalEarnings(Game $game)
    {
        $maxEarning = $game->earnings()->max('earning');
        $earnings = $game->earnings()
        ->where('earning', $maxEarning)
        ->get();
        return $this->collection($earnings, new GameWinnerEarningTransformer);
    }
}
