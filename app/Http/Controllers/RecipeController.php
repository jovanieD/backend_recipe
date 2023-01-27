<?php

namespace App\Http\Controllers;

use App\Http\Resources\RecipeResource;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     public function index()
    {

        try {
            $response = [];
            $free = Recipe::where('status', '=', 'free')->get();
            $sale = Recipe::where('status', '=', 'sale')->get();
            $data = RecipeResource::collection($sale);

            $r = array($free, $sale);

            return response($r);

        } catch (\Exception $e) {
            return response()->json($e);
        }
    }


    // public function sale()
    // {

    //     try {
    //         $sale = Recipe::where('status', '=', 'sale')->get();
    //         $data = RecipeResource::collection($sale);

    //         return response()->json($data);

    //     } catch (\Exception $e) {
    //         return response()->json($e);
    //     }
    // }

    public function searchById($id)
    {
        $response = [];
        try {
            $recipe = Recipe::whereRaw('id = '.$id.'')->get();
            $response["recipe"] = $recipe;
            $response["code"] = 200;
        } catch (\Exception $e) {
            $response["errors"] = "Cannot be found";
            $response["code"] = 400;
        }
        return response($response, $response["code"]);
    }

    //search by tag
    public function searchbyTag($tag)
    {

            $recipe = Recipe::where('tag', 'like', "%{$tag}%")->get();
            return $recipe;

    }

    public function store(Request $request)
    {
        //validation
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'tag' => 'required',
            'category' => 'required',
            'img_url' => 'required',
            'ingredients' => 'required',
            'procedures' => 'required',
            'user_id' => 'required',
            'status' => 'required'

        ]);

        $response = [];

        //check the validation if there are errors

        if ($validation->fails()) {
            $response["errors"] = $validation->errors();
            $response["code"] = 400;
        } else {
            DB::beginTransaction();
            try {
                //save
                $data = $request->all();
                $fileName = time().$request->file('img_url')->getClientOriginalName();
                $path = $request->file('img_url')->storeAs('images', $fileName, 'public');
                $data["img_url"] = '/storage/'.$path;
                $data['user_id'] = Auth::user()->id;
                $recipe = Recipe::create($data);
                DB::commit();
                $response["last_inserted_id"] = $recipe->id;
                $response["code"] = 200;
            } catch (\Exception $e) {
                DB::rollback();
                $response["errors"] = ["message" => "Recipe is not created" . $e];
                $response["code"] = 400;
            }
        }
        return response($response, $response["code"]);
    }

    public function update(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required|max:255',
            'tag' => 'required',
            'category' => 'required',
            'img_url' => 'required',
            'procedures' => 'required'
        ]);

        $response = [];

        //check the validation if there are errors

        if ($validation->fails()) {
            $response["errors"] = $validation->errors();
            $response["code"] = 400;
        } else {
            DB::beginTransaction();
            try {
                $recipe = Recipe::where("id", $id)->update($request->all());
                DB::commit();
                $response["last_updated_id"] = $id;
                $response["code"] = 200;
            } catch (\Exception $e) {
                DB::rollback();
                $response["errors"] = ["Recipe is not updated" . $e];
                $response["code"] = 400;
            }
        }
        return response($response, $response["code"]);
    }

    public function destroy($id)
    {
        //
        $response = [];
        DB::beginTransaction();
        try {
            $recipe = Recipe::find($id)->delete($id);
            DB::commit();
            $response["last_id_deleted"] = $id;
            $response["code"] = 200;
        } catch (\Exception $e) {
            DB::rollback();
            $response["error"] = ["Failed to Delete" . $e];
            $response["code"] = 400;
        }
        return response($response, $response["code"]);
    }
}
