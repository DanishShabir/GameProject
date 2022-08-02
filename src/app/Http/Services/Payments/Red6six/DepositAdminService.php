<?php

namespace App\Http\Services\Payments\Red6six;

use App\Exceptions\ErrorException;
use App\Models\Deposit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DepositAdminService
{
    /**
     * @var DepositService
     */
    private $depositService;

    public function __construct(DepositService $depositService)
    {
        $this->depositService = $depositService;
    }

    /**
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function getAll(array $data) : LengthAwarePaginator
    {
        $deposit = new Deposit();

        if (isset($data['status'])) {
            $deposit = $deposit->ofStatus($data['status']);
        }

        if (isset($data['payment_method'])) {
            $deposit->ofPaymentMethod($data['payment_method']);
        }

        return $deposit->paginate(10);
    }

    /**
     * @param array $data
     * @return void
     * @throws ErrorException
     */
    public function addCredits(array $data)
    {
        Log::info(__METHOD__ . " -- Admin is adding credits : ", $data);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            Log::error(__METHOD__ . " -- Email does not exist ", [$data['email']]);
            throw new ErrorException('exception.email_not_exist');
        }

        $paymentLink = $user->links()->create([
            'user_id' => $user->id,
            'link_id' => (string)Str::orderedUuid()
        ]);

        $data['payment_transaction_id'] = $paymentLink->link_id;
        $data['amount'] = $data['credits'];
        $data['payment_status'] = 'approved';

        return $this->depositService->processDeposit($data);
    }

    public function downloadCsv($deposits)
    {
        $uniqueId = uniqid();
        $fileName = "Deposits-{$uniqueId}.csv";

        $deposits = $deposits->getData()->data;
        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Name', 'Amount', 'Completed At', 'Payment Method', 'Email', 'Phone', 'Status');

        $callback = function() use($deposits, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($deposits as $deposit) {
                $row['Name']  = $deposit->user->first_name . $deposit->user->last_name;
                $row['Amount']    = $deposit->amount;
                $row['Completed At']    = Carbon::parse($deposit->completed_at)->format('d/m/Y');
                $row['Payment Method']  = $deposit->paymentMethod->name;
                $row['Email']  = $deposit->user->email;
                $row['Phone']  = $deposit->user->phone;
                $row['Status']  = $deposit->status->name;

                fputcsv($file, array($row['Name'], $row['Amount'], $row['Completed At'], $row['Payment Method'], $row['Email'], $row['Phone'], $row['Status']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
