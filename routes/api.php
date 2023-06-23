<?php

use App\Http\Controllers\API\Admin\AuthenController;
use App\Http\Controllers\API\CategoriesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// default
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

 // all Routes /  api must be authenticated
Route::group(['middleware' => ['api','checkPassword' , 'changeLanguage']] , function () {  // 'namespace' => 'API'   --> folder
        Route::post('get_main_categories', [CategoriesController::class, 'index']);
        Route::post('get_categories_byID', [CategoriesController::class, 'categoryById']);
        Route::post('change_category_status', [CategoriesController::class, 'changeStatus']);

        //Route::post('login', [AuthenController::class, 'login']);

        // login and logout (admin)
        Route::group(['prefix' => 'admin'], function () {
            Route::post('login', [AuthenController::class, 'login']);
            Route::post('logout', [AuthenController::class, 'logout'])->middleware('auth.guard:admin_api');
        });


        //login and logout (user)
        Route::group(['prefix'=>'user'],function (){
            Route::post('login', [\App\Http\Controllers\API\User\AuthenController::class , 'login']);
        });

        // user
        Route::group(['prefix'=>'user' , 'middleware'=>'auth.guard:user_api'],function (){
            Route::post('profile',function (){
                //return 'Only authenticated user can reach me';
                return \Auth::user(); // authenticated user data
            });
        });


});


Route::group(['middleware' => ['api', 'checkPassword', 'changeLanguage', 'checkAdminToken:admin_api'], 'namespace' => 'API'], function () {
    Route::post('offers', [CategoriesController::class, 'index']);
});


