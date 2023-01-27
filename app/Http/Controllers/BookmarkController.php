<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BookmarkController extends Controller
{
    public function index()
    {
        $response = [];
        try {
            $bookmarks = Bookmark::with('recipe', 'user')->get();
            $response["bookmarks"] = $bookmarks;
            $response["code"] = 200;
        } catch (\Exception $e) {
            $response["errors"] = ["message" => "Unable to get the bookmarks! $e"];
            $response["code"] = 400;
        }
        return response($response, $response["code"]);
    }

    public function store(Request $request)
    {
        $user = Auth::user()->id;
        $response = [];
        try {
            $bookmark = Bookmark::create([
                'user_id' => $user,
                'recipe_id' => $request->recipe_id,
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


    public function destroy($id)
        {
            DB::beginTransaction();
            $response=[];
            try {
                $bookmark = Bookmark::findOrFail($id)->delete();
                DB::commit();
                $response["last_deleted_id"] = $id;
                $response["code"] = 200;
            }catch(\Exception $e) {
                DB::rollBack();
                $response["errors"] = ["message"=> "Unable to delete this bookmark $e"];
                $response["code"] = 400;
            }

            return response($response, $response["code"]);
        }
}
