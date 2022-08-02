<?php

use Illuminate\Database\Seeder;
use App\Models\Game;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\GameExtension;
use App\Models\GamePlayer;
use App\Models\Currency;
use Illuminate\Support\Carbon;

class PayoutSeeder extends Seeder
{
    private $amounts = [
        'singlepay' => [
            'pot_value' => 30000,
            'jackpot_value' => 50000,
            'status' => 'live'
        ],
        'multipay' => [
            'pot_value' => 120000,
            'jackpot_value' => 50000,
            'status' => 'live'
        ],
        'megapay' => [
            'pot_value' => 500000,
            'jackpot_value' => 50000,
            'status' => 'live'
        ],
        'tripplepay' => [
            'pot_value' => 60000,
            'jackpot_value' => 50000,
            'status' => 'live'
        ],
        'tripplepayyes' => [
            'pot_value' => 74000,
            'jackpot_value' => 50000,
            'status' => 'live'
        ]
    ];

    private $gamePlayers = [
        "1" => [
            'user_id' => 1,
            'number_of_times_played' => 5,
            'highest_score' => 9000,
            'shortest_duration' => 20,
            'status' => 'notplaying'
        ],
        "2" => [
            'user_id' => 2,
            'number_of_times_played' => 8,
            'highest_score' => 5000,
            'shortest_duration' => 20,
            'status' => 'notplaying'
        ],
        "3" => [
            'user_id' => 3,
            'number_of_times_played' => 4,
            'highest_score' => 4000,
            'shortest_duration' => 30,
            'status' => 'notplaying'
        ],
        "4" => [
            'user_id' => 4,
            'number_of_times_played' => 2,
            'highest_score' => 3000,
            'shortest_duration' => 20,
            'status' => 'notplaying'
        ],
        "5" => [
            'user_id' => 5,
            'number_of_times_played' => 4,
            'highest_score' => 2000,
            'shortest_duration' => 40,
            'status' => 'notplaying'
        ],
        "6" => [
            'user_id' => 6,
            'number_of_times_played' => 4,
            'highest_score' => 1000,
            'shortest_duration' => 60,
            'status' => 'notplaying'
        ],
        "7" => [
            'user_id' => 7,
            'number_of_times_played' => 4,
            'highest_score' => 500,
            'shortest_duration' => 10,
            'status' => 'notplaying'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Currency::class)->create();

        $games = [];
        $games[] = factory(Game::class)->create($this->amounts['singlepay']);
        $games[] = factory(Game::class)->create($this->amounts['multipay']);
        $games[] = factory(Game::class)->create($this->amounts['megapay']);
        $games[] = factory(Game::class)->create($this->amounts['tripplepay']);
        $games[] = factory(Game::class)->create($this->amounts['tripplepayyes']);

        $game1Extensions = factory(GameExtension::class, 4)->create(['game_id' => 1]);
        $game2Extensions = factory(GameExtension::class, 4)->create(['game_id' => 2]);
        $game3Extensions = factory(GameExtension::class, 4)->create(['game_id' => 3]);
        $game4Extensions = factory(GameExtension::class, 4)->create(['game_id' => 4]);
        $game4Extensions = factory(GameExtension::class, 4)->create(['game_id' => 5]);

        $users = factory(User::class, 7)->create();

        $admin = factory(User::class)->create([
            'email' => 'admin@admin.com',
            'is_admin' => 1
        ]);

        factory(UserWallet::class)->create(['user_id' => $admin->id]);
        
        foreach($users as $user) {
            factory(UserWallet::class)->create(['user_id' => $user->id]);
            foreach($games as $game) {
                GamePlayer::create(array_merge([
                    'game_id' => $game->id
                ], $this->gamePlayers[$user->id]));
            }
        }
    }
}
