<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['api']], function () {

});

Route::post('/auth/login', 'Api\Auth\AuthController@login');

Route::group(['middleware' => 'api', 'prefix' => 'planmanagers'], function(){
    Route::post('/auth/login', 'Api\Auth\PlanManagerController@login');

    Route::get('/participant/list', 'Api\PlanManager\ParticipantsController@list');

    Route::get('/participant/details/{id}', 'Api\PlanManager\ParticipantsController@details');

    Route::get('/plans/participant/{participant_id}/', 'Api\PlanManager\PlansController@list');

    Route::get('/plans/participant/{participant_id}/plan/{plan_id}', 'Api\PlanManager\PlansController@details');

    Route::get('/serviceprovider/list', 'Api\PlanManager\ServiceProvidersController@list');

    Route::get('/supportcoordinator/list', 'Api\PlanManager\SupportCoordinatorsController@list');

});


Route::group(['middleware' => ['verifyDatabaseConnection'], 'prefix' => 'participants'], function(){
    Route::post('/auth/login', 'Api\Auth\ParticipantsController@login');
    Route::post('/forgotpassword', 'Api\Auth\ParticipantsController@forgotPassword');
    Route::post('/validate-otp', 'Api\Auth\ParticipantsController@validateOtp');
    Route::post('/reset-password', 'Api\Auth\ParticipantsController@resetPassword');
    Route::post('/auth/logout', 'Api\Auth\ParticipantsController@logOut');
    Route::post('/change/password', 'Api\Auth\ParticipantsController@changePassword');
    Route::post('/app-access-enquiry', 'Api\Auth\ParticipantsController@appAccessEnquiry');

    Route::get('/participant/list', 'Api\Participant\ParticipantsController@list');

    Route::get('/participant/details/{id}', 'Api\Participant\ParticipantsController@details');

    Route::get('/plans/participant/{participant_id}/', 'Api\Participant\PlansController@list');

    Route::get('/plans/participant/{participant_id}/plan/{plan_id}', 'Api\Participant\PlansController@details');

    Route::get('/serviceprovider/list', 'Api\Participant\ServiceProvidersController@list');

    Route::get('/supportcoordinator/list', 'Api\Participant\SupportCoordinatorsController@list');

    Route::get('/invoice/list', 'Api\Participant\ParticipantsController@invoiceList');

    Route::get('/refresh/token', 'Api\Auth\ParticipantsController@refresh');


});

