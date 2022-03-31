<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\UserAuthController;

Route::controller(UserAuthController::class)->prefix("user")->group(function() {
    Route::post("login", "loginUser");
});

Route::controller(UserController::class)->prefix("user")->group(function() {

    //user curd operation routes
    Route::post("create", "create");
    Route::post("update/{id}", "update");
    Route::get("delete/{id}", "delete");
    Route::get("get/{id}", "getUser");

    //route to update hobbies for user
    Route::middleware("auth:api")->post("update-hobby", "updateHobby");


    //route to fetch user with given hobby only access for admin user
    Route::middleware(["auth:api", "is_admin"])->get("hobby/{hobby}", "fetchUserWithHobby");
});