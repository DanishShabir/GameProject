<?php

namespace App\Http\Services\Games;

use App\Exceptions\ErrorException;
use App\Http\Services\BaseService;
use App\Models\Game;
use App\Models\GameConfiguration;
use App\Models\GamePlayer;
use App\Models\GameTransaction;
use App\Models\GameWinnerEarnings;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GameAdminService extends BaseService
{
    /**
     * @var GameConfiguration
     */
    private GameConfiguration $config;

    /**
     * @var GamePlayerService
     */
    private GamePlayerService $gamePlayerService;

    public function __construct(GamePlayerService $gamePlayerService)
    {
        $this->config = GameConfiguration::first();
        $this->gamePlayerService = $gamePlayerService;
    }

    /**
     * @param array $data
     * @return Collection
     */
    public function getAll(array $data) : Collection
    {
        if ($data) {
           return Game::where('status', $data['status'])->get();
        }

        return Game::all();

    }

    /**
     * @param array $data
     * @return Game
     */
    public function store(array $data) : Game
    {
        $customData = [];

        if (!isset($data['amount_of_balls_to_fire'])) {
            $customData['amount_of_balls_to_fire'] = $this->config->amount_of_balls_to_fire;
        }

        if (!isset($data['total_attempts'])) {
            $customData['total_attempts'] = $this->config->total_attempts;
        }

        $customData['number_of_winners'] = (0 == $data['entry_fee']) ? 0 : 30;
        $customData['status'] = Game::STATUS_NOT_STARTED;

        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date'] = Carbon::parse($data['end_date']);

        Log::info(__METHOD__ . " -- Admin create a new game:", array_merge($data, $customData));

        $game = Game::create(array_merge($data, $customData));

        $game->maps()->create([
            'map_id' => $data['map_id']
        ]);

        return $game;
    }

    /**
     * @param Game $game
     * @param array $data
     * @return Game
     */
    public function update(Game $game, array $data) : Game
    {
        Log::info(__METHOD__ . "update game data : ", $data);
        Log::info(__METHOD__ . " -- Admin updated the game:", $data);

        $data['start_date'] = Carbon::parse($data['start_date']);
        $data['end_date'] = Carbon::parse($data['end_date']);

        $status = $data['status'];

        if ($status === 'finished') {
            $data['status'] = Game::STATUS_WAITING_FOR_PAYOUT;
        }

        $game->update($data);

        Log::info(__METHOD__ . "status : ", [$status]);

        if ($status === 'finished') {
            Log::info(__METHOD__ . " -- Game finished by Admin:", $game->toArray());
            Log::info(__METHOD__ . " -- Expiring game player sessions:");

            $gamePlayers = GamePlayer::where(['status' => GamePlayer::STATUS_PLAYING, 'game_id' => $game->id])->get();

            foreach ($gamePlayers as $gamePlayer) {
                $this->gamePlayerService->expireGameSession($gamePlayer);
            }
        }

        return $game;
    }

    /**
     * @param Game $game
     * @return bool
     * @throws ErrorException
     */
    public function delete(Game $game) : bool
    {
        Log::alert(__METHOD__ . " -- Admin delete the game:", $game->toArray());
        if ($game->status === Game::STATUS_LIVE) {
            Log::error(__METHOD__ . " -- live games cannot be deleted by admin", $game->toArray());
            throw new ErrorException('exception.live_game_delete_error');
        }
        return $game->delete();
    }

    /**
     * @return Collection
     * @throws ErrorException
     */
    public function stopGame(): Collection
    {
        Log::alert(__METHOD__ . " -- Admin attempt to stop games");

        $games = Game::where(['status' => 'live'])->get();

        if (!isset($games)) {
            Log::error(__METHOD__ . ' -- No live game exists');
            throw new ErrorException('exception.live_game_not_exists');
        }

        Log::info(__METHOD__ . " -- Games to be stopped immediately:", $games->toArray());

        try {
            DB::beginTransaction();

            foreach ($games as $game) {
                $game->update([
                    'status' => Game::STATUS_ENDED_BY_ADMIN,
                    'pot_value' => 0,
                ]);

                $gameTransactions = $game->gameTransactions()
                    ->where('type', GameTransaction::TYPE_PAY_VIA_WALLET)
                    ->get();

                Log::info(__METHOD__ . " -- Game Transactions:", $gameTransactions->toArray());

                $alreadyRefunded = $game->gameTransactions()
                    ->where('type', GameTransaction::TYPE_REFUND)
                    ->count();

                Log::info(__METHOD__ . " -- Refunded:", [$alreadyRefunded]);

                if (!$alreadyRefunded) {
                    foreach ($gameTransactions as $gameTransaction) {

                        $playerWallet = $gameTransaction->wallet()->first();

                        $gameTransaction = $playerWallet->gameTransactions()->create([
                            'user_id' => $playerWallet->user_id,
                            'game_id' => $game->id,
                            'amount' => $gameTransaction->amount,
                            'type' => GameTransaction::TYPE_REFUND,
                            'status' => GameTransaction::STATUS_APPROVED
                        ]);
                        Log::alert(__METHOD__ . " -- Refund Game Transaction:", $gameTransaction->toArray());

                        $gameTransaction->walletTransactions()->create([
                            'user_id' => $playerWallet->user_id,
                            'wallet_id' => $playerWallet->id,
                            'wallet_type' => WalletTransaction::TYPE_WALLET_USER,
                            'amount' => $game->entry_fee,
                            'wallet_balance_before_tansaction' => $playerWallet->balance,
                            'wallet_balance_after_tansaction' => $playerWallet->balance + $gameTransaction->amount,
                            'type' => WalletTransaction::TYPE_CREDIT
                        ]);

                        $playerWallet->update([
                            'balance' => $playerWallet->balance + $gameTransaction->amount
                        ]);
                        Log::alert(__METHOD__ . " -- Player wallet updated:", $playerWallet->toArray());
                    }

                    $gamePlayers = $game->players()->get();

                    foreach ($gamePlayers as $gamePlayer) {

                        $gamePlayer->pivot->update([
                            'status' => GamePlayer::STATUS_NOT_PLAYING,
                            'token' => null
                        ]);
                    }
                }
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            Log::error(__METHOD__ . " -- Internal error occur in stopping game. All transactions are rollback");
            throw new ErrorException('exception.stop_game_error');
        }

        return $games;
    }

    public function updateWinners(Game $game, array $data)
    {
        $numberOfPlayersToPayAfter5 = $data['numberOfPlayersToPayAfter5'];
        $amountToShareBetweenRemainingPlayers = $data['amountToShareBetweenRemainingPlayers'];

        $adminPercentage = $data['adminPercentage'];
        $adminEarning = ($adminPercentage/100) * $game->pot_value;

        $players =  $data['players'];

        $amountToShare = array_sum($players) + $adminEarning + ($amountToShareBetweenRemainingPlayers * $numberOfPlayersToPayAfter5);

        $playersPaid = 0;
        $profit = $game->pot_value - $amountToShare;

        if ($amountToShare > $game->pot_value) {
            Log::error(__METHOD__ . ' -- Amount to share is greater than pot value --');
            throw new ErrorException('exception.total_amount_is_greater_than_pot_value');
        }

        $allPlayers = $game->players()
            ->orderBy('highest_score', 'DESC')
            ->orderBy('shortest_duration', 'ASC')
            ->get();

        for ($i = 0; $i < $numberOfPlayersToPayAfter5; $i++) {
            $players [] = $amountToShareBetweenRemainingPlayers;
        }

        foreach ($players as $index => $prize) {

            $earning = (int) $prize;
            if ($earning === 0) {
                continue;
            }

            $playersPaid ++;

            $player = $allPlayers[$index];

            $gameWinnerEarning = $game->earnings()->create([
                'user_id' => $player->id,
                'earning' => $earning,
                'status' => GameWinnerEarnings::STATUS_PENDING
            ]);

            $playerWallet = $player->wallets()->first();

            // player
            $gameTransaction = $this->createGameTransaction($game, $playerWallet, $earning, GameTransaction::TYPE_EARN, GameTransaction::STATUS_APPROVED);
            Log::alert(__METHOD__ . " -- Player game transaction : ", $gameTransaction->toArray());

            // create one for payout as well, because now user can't request for payout
            $gameTransaction = $this->createGameTransaction($game, $playerWallet, $earning, GameTransaction::TYPE_PAYOUT, GameTransaction::STATUS_PENDING);
            Log::alert(__METHOD__ . " -- Player game transaction for payout : ", $gameTransaction->toArray());

            Event::dispatch('game.ended', [$player, $gameWinnerEarning]);
        }

        $admin = User::where('is_admin', 1)->first();

        $adminWallet = $admin->wallets()->first();

        if ($adminEarning == 0) {
            Log::error(__METHOD__ . ' -- Admin Earning could be not zero --');
            throw new ErrorException('exception.earning_is_zero');
        }

        $adminWallet->update([
            'balance' => ($adminWallet->balance + $adminEarning)
        ]);

        Log::alert(__METHOD__ . " -- Admin wallet:", $adminWallet->toArray());

        // admin
        $gameTransaction = $this->createGameTransaction($game, $adminWallet, $adminEarning, GameTransaction::TYPE_EARN, GameTransaction::STATUS_APPROVED);
        $this->createWalletTransaction($gameTransaction, $adminWallet, $adminEarning);

        // profit
        // TODO:: Should profit needs to be added in admin wallet? If yes then do a wallet transaction
        if ($profit > 0) {
            $gameTransaction = $this->createGameTransaction($game, $adminWallet, $profit, GameTransaction::TYPE_PROFIT, GameTransaction::STATUS_APPROVED);
        }

        $game->admin_percentage = $adminPercentage;
        $game->admin_fee = $adminEarning;
        $game->profit = $profit;
        $game->players_paid = $playersPaid;
        $game->status = Game::STATUS_FINISHED;
        $game->save();
    }

    private function createGameTransaction(Game $game, UserWallet $userWallet, int $earning, string $type, string $status)
    {
        return $game->gameTransactions()->create([
            'user_id' => $userWallet->user_id,
            'wallet_id' => $userWallet->id,
            'wallet_type' => GameTransaction::TYPE_WALLET_USER,
            'amount' => $earning,
            'type' => $type,
            'status' => $status,
        ]);
    }

    private function createWalletTransaction($gameTransaction, UserWallet $userWallet, int $earning)
    {
        return $gameTransaction->walletTransactions()->create([
            'user_id' => $userWallet->user_id,
            'wallet_id' => $userWallet->id,
            'wallet_type' => WalletTransaction::TYPE_WALLET_USER,
            'amount' => $earning,
            'wallet_balance_before_tansaction' => $userWallet->balance,
            'wallet_balance_after_tansaction' => $userWallet->balance + $earning,
            'type' => WalletTransaction::TYPE_CREDIT
        ]);
    }
}
