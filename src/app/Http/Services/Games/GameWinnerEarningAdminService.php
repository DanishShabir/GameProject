<?php

namespace App\Http\Services\Games;

use App\Http\Services\BaseService;
use App\Models\Game;
use App\Models\GameTransaction;

class GameWinnerEarningAdminService extends BaseService
{
    private $game;
    private $adminFeePercent = 0;
    private $adminPotValue = 0;
    private $numberOfPlayers = 0;
    private $numberOfWinners = 0;
    private $winners = [];
    private $jackpotTarget = 0;
    private $finalPotValue = 0;
    private $remainingPot = 0;
    private $topFivePot = 0;

    public function setup(Game $game) {

        $this->game = $game;
        $this->adminFeePercent = $game->admin_fee_percentage;
        $this->adminPotValue = 0;
        $this->numberOfPlayers = $game->number_of_players_x;
        $this->numberOfWinners = $game->number_of_winners;
        $this->winners = [];
        $this->jackpotTarget = $game->jackpot_value;
        $this->finalPotValue = $this->remainingPot = $game->pot_value;
        $this->topFivePot = 0;

        $i = 1;
        while ($i <= $this->numberOfPlayers) {
            $this->winners[$i] = 0;
            $i++;
        }

    }

    public function getWinners() {
        return $this->winners;
    }

    public function getAdminPotValue() {
        return $this->adminPotValue;
    }

    public function getRemainingPot() {
        return $this->remainingPot;
    }

    /**
     * @param Game $game
     */
    public function calculateUserEarning(Game $game)
    {
        $this->setup($game);
        $this->calculateEarning();
    }

    /**
     * @param Game $game
     */
    public function calculateAdminEarning(Game $game)
    {
        return $this->adminPotValue;
    }

    public function calculateEarning()
    {
        $this->adminPotValue = ($this->adminFeePercent/100) * $this->remainingPot; // variable % to admin
        $this->remainingPot -= $this->adminPotValue;

        // Jackpot Target + 50%
        $multiPayMinimum = ((50/100) * $this->jackpotTarget) + $this->jackpotTarget;

        if ($this->finalPotValue <= $this->jackpotTarget) {
            $this->singlePay();
        } elseif ($this->finalPotValue <= $multiPayMinimum) {
            $this->triplePay();
        } elseif ($this->finalPotValue > $multiPayMinimum) {

            $megaPayMinimum = (20/100) * $this->remainingPot;

            if ($megaPayMinimum >= $this->jackpotTarget) {
                $this->winners[1] = floor((25/100) * $this->remainingPot);
            } else {
                $this->winners[1] = floor($this->jackpotTarget);
            }

            $this->remainingPot -= $this->winners[1];

            $this->topFivePot = (50/100) * $this->remainingPot;

            $this->calculateTopFiveWinners();
            $this->splitRemaining();

        }

        // Sweep up remaining pot (left overs and division remainders) into adminPot
        $this->adminPotValue += $this->remainingPot;
    }

    private function singlePay()
    {
        $this->winners[1] = floor($this->remainingPot); //70% to first winner 
        $this->remainingPot -= $this->winners[1];
    }

    private function triplePay()
    {
        if ($this->remainingPot >= $this->jackpotTarget) {

            $this->winners[1] = $this->jackpotTarget;
            $this->remainingPot -= $this->jackpotTarget;

            if ($this->remainingPot >= 2) {

                if ( $this->numberOfPlayers >= 3 ) {

                    $runnerUpWinnings = floor($this->remainingPot / 2);
                    $this->winners[2] = $this->winners[3] = $runnerUpWinnings;
                    $this->remainingPot -= ($runnerUpWinnings * 2);

                } elseif ( $this->numberOfPlayers == 2 ) {

                    $this->winners[2] = floor($this->remainingPot);
                    $this->remainingPot -= $this->winners[2];

                }

            } 

        } else {
            $this->winners[1] = floor($this->remainingPot);
            $this->remainingPot -= $this->winners[1];
        }
    }


    private function calculateTopFiveWinners()
    {
        $payoutStructureRules = [
            1 => [],
            2 => [100],
            3 => [70, 30],
            4 => [50, 30, 20],
            5 => [40, 30, 20, 10]
        ];

        $payoutStructure = $this->numberOfPlayers < 5 ? $this->numberOfPlayers : 5;

        $i = 2;
        foreach ($payoutStructureRules[$payoutStructure] as $p) {
            $amount = ($p / 100) * $this->topFivePot;
            $this->winners[$i] = floor($amount);
            $this->remainingPot -= $this->winners[$i];
            $i++;
        }
    }

    private function splitRemaining()
    {
        $remainingWinners = $this->numberOfWinners;

        if ( $this->numberOfPlayers < $this->numberOfWinners )
            $remainingWinners = $this->numberOfPlayers;
        
        $remainingWinners -= 5;

        if ( $remainingWinners < 1 )
            return;

        $winningAmount = floor($this->remainingPot / $remainingWinners);

        if ( $winningAmount < 1 )
            return;

        for ( $i = 6; $i < ($remainingWinners + 6); $i++ ) {
            $this->winners[$i] = $winningAmount;
            $this->remainingPot -= $this->winners[$i];
        }
    }
}
