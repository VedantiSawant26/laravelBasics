<?php

// use App\Http\Controllers\MailController;

use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/logout', [UserController::class, 'logout']);
// home login signup details
Route::prefix('home')->group(function () {
    // login
    Route::get('/login', [UserController::class, 'showLogin']);
    Route::post('/login', [UserController::class, 'loginData']);
    // signup page
    Route::get('/signup', [UserController::class, 'userData']);
    Route::post('/signup/add', [UserController::class, 'addUser']);
});

// middleware
Route::middleware(['isUser'])->group(function () {
    Route::get('/dashbord', [UserController::class, 'showdashbord']);

    // show user details
    Route::prefix('user')->group(function () {
        Route::get('/userDetails', [UserController::class, 'showUserData']);
        // delete user data
        Route::post('/delete', [UserController::class, 'deleteData']);
        // edit user data
        Route::post('/edit', [UserController::class, 'editUser']);
        // userImages details
        Route::post('/userImgs', [UserController::class, 'addUserImgs']);
        // Delete userImags
        Route::post('/deleteImgs', [UserController::class, 'deleteImgs']); 
        // Excel file
        Route::post('/excelFile', [UserController::class, 'showExcel']); 
    });

    // show role details
    Route::prefix('roles')->group(function () {
        Route::get('/showRoles', [UserController::class, 'showRolesData']);
        Route::post('/add', [UserController::class, 'addRoles']);
    });

    // addresss details
    Route::prefix('address')->group(function () {
        Route::get('/{userId}', [UserController::class, 'addAddress']); // indexAddress

        Route::post('/add', [UserController::class, 'addAddressData']); //addAddress
        // delete user address data
        Route::post('/delete', [UserController::class, 'deleteAddress']); //deleteAddress
        // edit user address data
        Route::post('/edit', [UserController::class, 'editAddress']); //updateAddress
        // add address excel
        Route::post('/addressFile', [UserController::class, 'showAddExcel']); 
    });

});

    // Route pincode
    Route::get('/pincode', [UserController::class, 'searchPinCode']);

    // forget pass
    Route::get('/forgetPass', [UserController::class, 'forgetPass']);
    Route::post('/forgtpass/add', [UserController::class, 'forgetData']);
    Route::post('/forgtpass/verifyOtp', [UserController::class, 'verifyOtp']);
    Route::post('/forgtpass/changePass', [UserController::class, 'changePass']);


