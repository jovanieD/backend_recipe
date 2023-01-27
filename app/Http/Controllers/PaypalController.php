<?php

namespace App\Http\Controllers;

use App\Models\PurchasedRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    public function createpaypal(Request $request)
    {
        $user = Auth::user()->id;
        $response = [];
        try {
            $bookmark = PurchasedRecipe::create([
                'user_id' => $user,
                'recipe_id' => 1
            ]);
            DB::commit();
            $response["last_inserted_id"] = $bookmark->id;
            $response["code"] = 200;
        } catch (\Exception $e) {
            DB::rollBack();
            $response["errors"] = ["message" => "Cannot add bookmark! $e"];
            $response["code"] = 400;
        }

        return response($response, $response["code"]);



    }


    public function processPaypal(Request $request)
    {
        $price = $request->price;
        $id = $request->id;


        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('processSuccess'),
                "cancel_url" => route('processCancel'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "PHP",
                        "value" => $price
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {

            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            return redirect()
                ->with('error', 'Something went wrong.');
        } else {
            return redirect()
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function processSuccess(Request $request)
    {
        $id = $request->id;

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return redirect()
                ->route('createpaypal')
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function processCancel(Request $request)
    {
        return redirect()
            ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }
}
