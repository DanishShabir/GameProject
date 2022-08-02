<?php

namespace Tests\Feature\Payouts;

use App\Models\User;
use App\Models\Game;
use App\Models\GamePlayer;
use App\Models\GameTransaction;
use Illuminate\Support\Facades\Artisan;
use Tests\BaseTestCase;
use App\Models\GameWinnerEarnings;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PayoutTest extends BaseTestCase
{
    /**
     * @test
     */
    public function payoutTestSuccess()
    {
        $this->artisan('db:seed', ['--class' => 'PayoutSeeder']);
        $this->artisan('game:end');
        
        $singlepayTransactions = GameTransaction::where('game_id', 1)->get();
        $multipayTransactions = GameTransaction::where('game_id', 2)->get();
        $megapayTransactions = GameTransaction::where('game_id', 3)->get();
        $tripplepayTransactions = GameTransaction::where('game_id', 4)->get();
        $tripplepayyesTransactions = GameTransaction::where('game_id', 5)->get();

        //Singlepay transactions test
        $this->assertEquals(9000, $singlepayTransactions->where('user_id', 8)->first()->amount);
        $this->assertEquals(21000, $singlepayTransactions->where('user_id', 1)->first()->amount);

        //Multipay transactions test
        $this->assertEquals(36000, $multipayTransactions->where('user_id', 8)->first()->amount);
        $this->assertEquals(50000, $multipayTransactions->where('user_id', 1)->first()->amount);
        $this->assertEquals(6800, $multipayTransactions->where('user_id', 2)->first()->amount);
        $this->assertEquals(5100, $multipayTransactions->where('user_id', 3)->first()->amount);
        $this->assertEquals(3400, $multipayTransactions->where('user_id', 4)->first()->amount);
        $this->assertEquals(1700, $multipayTransactions->where('user_id', 5)->first()->amount);
        $this->assertEquals(8500, $multipayTransactions->where('user_id', 6)->first()->amount);
        $this->assertEquals(8500, $multipayTransactions->where('user_id', 7)->first()->amount);

        //Megapay transactions test
        $this->assertEquals(150000, $megapayTransactions->where('user_id', 8)->first()->amount);
        $this->assertEquals(87500, $megapayTransactions->where('user_id', 1)->first()->amount);
        $this->assertEquals(52500, $megapayTransactions->where('user_id', 2)->first()->amount);
        $this->assertEquals(39375, $megapayTransactions->where('user_id', 3)->first()->amount);
        $this->assertEquals(26250, $megapayTransactions->where('user_id', 4)->first()->amount);
        $this->assertEquals(13125, $megapayTransactions->where('user_id', 5)->first()->amount);
        $this->assertEquals(65625, $megapayTransactions->where('user_id', 6)->first()->amount);
        $this->assertEquals(65625, $megapayTransactions->where('user_id', 7)->first()->amount);

        //Tripplepay jackpot no transactions test
        $this->assertEquals(18000, $tripplepayTransactions->where('user_id', 8)->first()->amount);
        $this->assertEquals(42000, $tripplepayTransactions->where('user_id', 1)->first()->amount);

        //Tripplepay jackpot yes transactions test
        $this->assertEquals(22200, $tripplepayyesTransactions->where('user_id', 8)->first()->amount);
        $this->assertEquals(50000, $tripplepayyesTransactions->where('user_id', 1)->first()->amount);
        $this->assertEquals(900, $tripplepayyesTransactions->where('user_id', 2)->first()->amount);
        $this->assertEquals(900, $tripplepayyesTransactions->where('user_id', 3)->first()->amount);
        
    }
}
