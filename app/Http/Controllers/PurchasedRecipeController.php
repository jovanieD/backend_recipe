<?php

namespace App\Http\Controllers;

use App\Models\PurchasedRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchasedRecipeController extends Controller
{
    public function index()
    {
        $response = [];
        try {
            $user = Auth::user()->id;
            $purchasedrecipe = PurchasedRecipe::whereRaw('user_id = '.$user.'')->with('recipe')->get();
            $response["Purchased Recipe"] = $purchasedrecipe;
            $response["code"] = 200;

        } catch (\Exception $e) {
            $response["errors"] = ["message" => "Unable to get the Purchased Recipe! $e"];
            $response["code"] = 400;
        }
        return response($response, $response["code"]);
    }


}
