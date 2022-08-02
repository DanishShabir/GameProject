<?php

namespace App\Http\Controllers\Payouts;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameTransaction;
use App\Models\GameWinnerEarnings;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\UserWallet;
use App\Models\GameExtension;
use App\Models\Currency;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Http\Request;
use App\Http\Services\Games\GameWinnerEarningAdminService;
use Illuminate\Support\Facades\Log;

class PayoutTestController extends Controller
{
    /**
     * @var GameWinnerEarningAdminService
     */
    private GameWinnerEarningAdminService $service;

    public function __construct(GameWinnerEarningAdminService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {

        $game = new Game;
        
        $game->admin_fee_percentage = $request->admin_fee_percent;
        $game->number_of_players_x = $request->number_of_players;
        $game->number_of_winners = $request->number_of_winners;
        $game->jackpot_value = $request->jackpot_target;
        $game->pot_value = $request->end_pot_value;

        $this->service->calculateUserEarning($game);
        $winners = $this->service->getWinners();
        $adminEarning = $this->service->getAdminPotValue();

        echo "*******************************<br>";
        echo "Jackpot Target: £$request->jackpot_target <br>";
        echo "Pot Value: £$request->end_pot_value      <br>";
        echo "Admin fee: £$adminEarning   <br>";
        echo "*******************************<br>";
        
        echo "##### WINNERS #####<br>";
        
        foreach($winners as $player => $prize) {
            echo "Player $player - $prize<br>";
        }
        
    }

}
