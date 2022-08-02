<?php

namespace App\Http\Controllers\Games;

use App\Http\Controllers\Controller;
use App\Http\Services\Games\GameFreeCreditsService;
use App\Http\Services\Games\GameHistoryService;
use App\Http\Transformers\Games\GameFreeCreditsTransformer;
use App\Http\Transformers\Games\GameHistoryTransformer;
use Illuminate\Http\JsonResponse;

class GameFreeCreditsController extends Controller
{
    /**
     * @var GameFreeCreditsService/
     */
    private GameFreeCreditsService $service;

    /**
     * @var GameHistoryTransformer
     */
    private GameFreeCreditsTransformer $transformer;

    public function __construct(
        GameFreeCreditsService $service,
        GameFreeCreditsTransformer $transformer
    )
    {
        $this->service = $service;
        $this->transformer = $transformer;
    }

    public function index() : JsonResponse
    {
        $gamesWallets = $this->service->getAll();

        return $this->success($gamesWallets, $this->transformer);
    }
}
