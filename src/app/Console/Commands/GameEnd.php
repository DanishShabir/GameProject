<?php

namespace App\Console\Commands;

use App\Http\Services\Games\GamePlayerService;
use App\Models\Game;
use App\Models\GamePlayer;
use Illuminate\Console\Command;
use App\Http\Services\Games\GameWinnerEarningAdminService;
use Illuminate\Support\Facades\Log;


class GameEnd extends Command
{
    /**
     * The name and signature of the console command.
     *
     */
    protected $signature = 'game:end';

    /**
     * The console command description.
     *
     */
    protected $description = 'End game and update winners';

    /**
     * @var GameWinnerEarningAdminService
     */
    private GameWinnerEarningAdminService $service;

    /**
     * @var GamePlayerService
     */
    private GamePlayerService $gamePlayerService;

    public function __construct(GameWinnerEarningAdminService $service, GamePlayerService $gamePlayerService)
    {
        parent::__construct();
        $this->service = $service;
        $this->gamePlayerService = $gamePlayerService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $games = Game::where(['status' => 'live'])->get();
        foreach ($games as $game) {
            if (
                ($game->is_deadline_reached && $game->game_ext_days == 0 ) ||
                ($game->is_deadline_reached && ($game->pot_value >= $game->jackpot_value)) ||
                ($game->is_extended && $game->pot_value >= $game->jackpot_value) ||
                ($game->is_extended && $game->extensions_count == MAX_GAME_EXTENSION_LIMIT)
            ) {
                $this->finishGame($game);
            }
        }
    }

    /**
     * @param Game $game
     */
    private function finishGame(Game $game) : void
    {
        $game->status = Game::STATUS_WAITING_FOR_PAYOUT;
        $game->save();

        Log::info(__METHOD__ . " -- Game finished by Job:", $game->toArray());

        Log::info(__METHOD__ . " -- Expiring game player sessions:");

        $gamePlayers = GamePlayer::where(['status' => GamePlayer::STATUS_PLAYING, 'game_id' => $game->id])->get();

        foreach ($gamePlayers as $gamePlayer) {
            $this->gamePlayerService->expireGameSession($gamePlayer);
        }
    }
}
