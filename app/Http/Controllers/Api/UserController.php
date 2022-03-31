<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\UpdateRequest;

class UserController extends Controller
{

    

    public function create(CreateRequest $request)
    {
        try {

            $params = $request->all();

            //if image found then store and add image name to db
            if ($file = $request->file('image')) {
                $path = $file->store('public/user');
                $name = $file->getClientOriginalName();
                $params["image"] = $name;
            }
            $user = User::create($params);
            return response()->json(["type" => "success", "message" => "User created.", "user" => $user]);

        } catch(\Exception $e) {
            return response()->json(["type" => "error", "message" => $e->getMessage()]);
        }
    }

    public function update(UpdateRequest $request)
    {
        try {

            $params = $request->all();
            $id = $request->id;

            //return if no id found
            if(empty($id)) {
                return response()->json(["type" => "error", "message" => "No user to update."]);
            }

            $user = User::find($id);
            //return if no user found with given id
            if(empty($user)) {
                return response()->json(["type" => "error", "message" => "No user to update."]);
            }

            //if image found then store and add image name to db
            if ($file = $request->file('image')) {
                $path = $file->store('public/user');
                $name = $file->getClientOriginalName();
                $params["image"] = $name;
            }

            $user = $user->update($params);

            return response()->json(["type" => "success", "message" => "User updated."]);
        } catch(\Exception $e) {
            return response()->json(["type" => "error", "message" => $e->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {

            $id = $request->id;

            //return if no id found
            if(empty($id)) {
                return response()->json(["type" => "error", "message" => "No user to delete."]);
            }

            $user = User::find($id);

            //return if no user found with given id
            if(empty($user)) {
                return response()->json(["type" => "error", "message" => "No user to delete."]);
            }

            $user->delete();
            return response()->json(["type" => "success", "message" => "User deleted."]);

        } catch(\Exception $e) {
            return response()->json(["type" => "error", "message" => $e->getMessage()]);
        }
    }

    public function getUser(Request $request)
    {
        try {

            $id = $request->id;

            //return if no id found
            if(empty($id)) {
                return response()->json(["type" => "error", "message" => "No user to found."]);
            }

            $user = User::find($id);

            //return if no user found with given id
            if(empty($user)) {
                return response()->json(["type" => "error", "message" => "No user to found."]);
            }
            return response()->json(["type" => "success", "details" => $user]);

        } catch(\Exception $e) {
            return response()->json(["type" => "error", "message" => $e->getMessage()]);
        }
    }

    public function updateHobby(Request $request)
    {
        try {
            $id = auth()->user()->id;

            //return if no id found
            if(empty($id)) {
                return response()->json(["type" => "error", "message" => "No user found."]);
            }
            $user = User::find($id);

            //return if no user found with given id
            if(empty($user)) {
                return response()->json(["type" => "error", "message" => "No user found."]);
            }

            //fetch hobbies from request and convert it to json then add to db 
            $hobbies = $request->get("hobbies");
            if(is_array($hobbies) && !empty($hobbies)) {
                $params["hobbies"] = json_encode($hobbies);
                $user->update($params);
            }

            return response()->json(["type" => "success", "message" => "Hobbies updated."]);

        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()]);
        }
    }

    public function fetchUserWithHobby(Request $request)
    {
        try {

            $hobby = $request->hobby;

            //find users with particular hobby
            $users = User::whereJsonContains("hobbies", $hobby)->get();

            return response()->json(["type" => "success", "users" => $users]);

        } catch(\Exception $e) {
            return response()->json(["type" => "error", "message" => $e->getMessage()]);
        }
    }
}
