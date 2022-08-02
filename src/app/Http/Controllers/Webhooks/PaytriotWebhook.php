<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Http\Services\Payments\Red6six\DepositService;
use App\Repositories\PaymentGateways\PaymentRepositoryInterface;
use Illuminate\Http\Request;

class PaytriotWebhook extends Controller
{
    /**
     * @var DepositService/
     */
    private DepositService $depositService;

    public function __construct(DepositService $depositService)
    {
        $this->depositService = $depositService;
    }

    public function store(Request $request)
    {
        if ($request['status'] === 'success') {
            $data['payment_transaction_id'] = $request['link_id'];
            $data['amount'] = $request['amount'];
            $data['payment_status'] = $request['status'] === 'success' ? 'approved' : 'rejected';
            $this->depositService->processDeposit($data);
        }
    }

}