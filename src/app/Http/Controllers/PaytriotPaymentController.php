<?php

namespace App\Http\Controllers;

use App\Http\Services\Merchant_Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaytriotPaymentController extends Controller
{
    public function test()
    {
        $paytriot = new Merchant_Api('https://api.paytriot.co.uk/api/merchant',
            '96973a3a5878dd96',
            '7362675fe7a43dad', 'true', '3573bb50041484f8f65d8035');

//        $abc = $paytriot->transferCardToAccount('123412341234', '062022', '123', '11117774', '60.00', 'USD');
//        $abc = $paytriot->getAccountStatus('11117774');
//        $abc = $paytriot->createAccount('user name', 'USD');
//        $abc = $paytriot->getAccountCards('11117774');
//        $abc = $paytriot->createPaymentRequestLink('creditcard', '10.50', '11117774', 'USD', null, null, null, null, 0, '2');
//        $abc = $paytriot->getPaymentRequestLinks('paypal', '', '', '', '', '', '', '', '', '', '');
//        dd($abc);

//        dd($abc);
//        $data = '{
//            "sender_card": "18",
//           "receiver_card": 16,
//           "amount": 1.01,
//           "currency": "USD",
//           "key": "96973a3a5878dd96",
//           "ts": "_TIMESTAMP_",
//           "sign": "_SIGN_"
//        }';

//        $client = new \GuzzleHttp\Client();
//
//        $response = $client->request('POST', 'https://api.paytriot.co.uk/api/merchant/v/1.0/function/transfer_c_to_c', [
//            'body' => $data,
//        ]);
//
//        dd($response->getBody()->getContents());
    }
}
