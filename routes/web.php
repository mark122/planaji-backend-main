<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\providertypes;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('home');
    }
});

Auth::routes();
Route::get('/enterprise/login/first2care', 'Auth\LoginController@enterpriselogin');
Route::get('/enterprise/login/axial', 'Auth\LoginController@enterpriselogin2');
Route::get('/enterprise/login/gentlecare', 'Auth\LoginController@enterpriselogin3');
Route::get('/enterprise/login/planontrack', 'Auth\LoginController@enterpriselogin4');

// Route::get('/image','ImageController@create');
// Route::post('/image','ImageController@store');
// Route::get('/{image}','ImageController@show');


// Route::get('encrypt', 'EncryptionController@encrypt');

// Route::get('decrypt', 'EncryptionController@decrypt');

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/home', 'HomeController@messageus');

Route::get('/login', 'Auth\LoginController@index')->name('login');

Route::get('/requestdemo', 'RequestDemoController@index')->name('requestdemo');

Route::post('/requestdemo/data', 'RequestDemoController@data')->name('demodata');

Route::post('/message', 'RequestDemoController@message')->name('message');

Route::get('/messagesentview', 'MessageUsController@index')->name('messagesentview');

Route::post('/register', 'Auth\RegisterController@store');

Route::get('/privacy-policy', 'PrivacyPolicyController@index');

Route::get('/terms-of-service', 'TermsOfServiceController@index');

Route::get('/support-policy', 'SupportPolicyController@index');

Route::get('/forgotpassword', 'ForgotPasswordController@index')->name('forgotpassword');

Route::post('/forgotpassword/data', 'ForgotPasswordController@data')->name('forgotpassword.send');

Route::get('/resetpassword', 'ResetPasswordController@index')->name('resetpassword');

Route::get('/resetpassword/success', 'ResetPasswordController@success')->name('resetpassword.success');

Route::post('/resetpassword/reset', 'ResetPasswordController@reset')->name('resetpassword.reset');


Route::get('/resetpasswordparticipant', 'ResetPasswordParticipantController@index')->name('resetpasswordparticipant');

Route::get('/resetpasswordparticipant/success', 'ResetPasswordParticipantController@success')->name('resetpasswordparticipant.success');

Route::post('/resetpasswordparticipant/reset', 'ResetPasswordParticipantController@reset')->name('resetpasswordparticipant.reset');

Route::group(['middleware' => ['auth']], function () {

    // ADMIN ROUTES

    Route::get('/dashboard', 'Admin\DashboardController@index')->name('dashboard');

    Route::get('/dashboard/emails', 'Admin\DashboardController@showDashboardEmails')->name('dashboard.emails');

    Route::get('/dashboard/download/{id}', 'Admin\DashboardController@download')->name('dashboard.download');

    Route::get('/settings', 'Admin\SettingsController@index')->name('settings');

    Route::post('/settings/', 'Admin\SettingsController@save')->name('settings.save');

    Route::get('/account', 'Admin\AccountController@index')->name('account');

    Route::post('/account/', 'Admin\AccountController@save')->name('account.save');

    Route::get('/participants', 'Admin\ParticipantsController@index')->name('participant');

    Route::post('/participants/', 'Admin\ParticipantsController@view')->name('participant.view');

    Route::get('/participants/{id}/profile', 'Admin\ParticipantsController@profile')->name('participant.profile');

    Route::get('/participants/{participant_id}/plans/{plan_id}', 'Admin\ParticipantsController@plans')->name('participant.plans');


    // Route::get('//participants-profile/{id}',function() {
    //     return redirect()->action('Admin\ParticipantsController@profile');
    // })->name('participant.profile');

    Route::get('generatepdf', 'Admin\StatementController@generatePDF')->name('generatepdf');



    Route::get('/service-providers', 'Admin\ServiceProvidersController@index');

    Route::get('/support-coordinators', 'Admin\SupportCoordinatorsController@index');

    Route::get('/invoices', 'Admin\InvoicesController@index')->name('invoices');

    Route::get('/invoices/{id}/view', 'Admin\InvoicesController@view')->name('invoice.view');

    Route::get('/invoices/{id}/edit', 'Admin\InvoicesController@edit')->name('invoice.edit');

    Route::get('/invoices/{id}/duplicate', 'Admin\InvoicesController@duplicate')->name('invoice.duplicate');

    Route::get('/invoices/add', 'Admin\InvoicesController@add')->name('invoice.add');

    Route::post('/invoice/uploadInvoice', 'Admin\InvoicesController@uploadInvoice')->name('invoice.uploadInvoice');

    Route::get('/users', 'Admin\UsersController@index');

    Route::get('/users', 'Admin\UsersController@loadrecords')->name('users.loadrecords');

    Route::get('/users/{id}/editrecord/', 'Admin\UsersController@editrecord')->name('users.editrecord');

    Route::post('/users', 'Admin\UsersController@saverecord')->name('users.saverecord');

    Route::post('/users/deleterecord', 'Admin\UsersController@deleterecord')->name('users.deleterecord');

    //participants
    // Route::get('/participants','Admin\ParticipantsController@loadphonebook')->name('participants.loadphonebook');


    // Route::get('/participants/{id}/editphonebook/','Admin\ParticipantsController@editphonebook')->name('participants.editphonebook');


    // Route::post('/participants','Admin\ParticipantsController@savephonebook')->name('participants.savephonebook');


    // Route::get('/participants/{id}/deletephonebook','Admin\ParticipantsController@deletephonebook')->name('participants.deletephonebook');

    //pricing guide
    Route::get('/pricing', 'Admin\PricingGuideController@loadrecords')->name('pricing.loadrecords');

    //participant
    Route::get('/participants', 'Admin\ParticipantsController@loadrecords')->name('participants.loadrecords');
    Route::get('/participants/{id}/editrecord/', 'Admin\ParticipantsController@editrecord')->name('participants.editrecord');
    Route::post('/participants', 'Admin\ParticipantsController@saverecord')->name('participants.saverecord');
    Route::post('/participants/deleterecord', 'Admin\ParticipantsController@deleterecord')->name('participants.deleterecord');
    Route::get('/participants/{id}/addinvoice/', 'Admin\ParticipantsController@addinvoice')->name('participants.addinvoice');
    //participant profile

    Route::get('/participants/loadrecordplans', 'Admin\ParticipantsController@loadrecordplans')->name('participants.loadrecordplans');
    Route::get('/participants/loadrecordplans/{id}/editrecordplan/', 'Admin\ParticipantsController@editrecordplan')->name('participants.editrecordplan');
    Route::get('/participants/loadrecordplans/getplan_contractno/', 'Admin\ParticipantsController@getplan_contractno')->name('participants.getplan_contractno');
    Route::post('/participants/saverecordplan', 'Admin\ParticipantsController@saverecordplan')->name('participants.saverecordplan');
    Route::post('/participants/deleterecordplan', 'Admin\ParticipantsController@deleterecordplan')->name('participants.deleterecordplan');




    //participant plans

    //plans

    Route::post('/participants/saverecordplandetails', 'Admin\ParticipantsController@saverecordplandetails')->name('participants.saverecordplandetails');

    Route::post('/participants/getoutcomedomains', 'Admin\ParticipantsController@getoutcomedomains')->name('participants.getoutcomedomains');

    Route::post('/participants/getstateditems', 'Admin\ParticipantsController@getstateditems')->name('participants.getstateditems');

    Route::get('/participants/loadrecordcapacitybuilding', 'Admin\ParticipantsController@loadrecordcapacitybuilding')->name('participants.loadrecordcapacitybuilding');

    Route::get('/participants/loadrecordcapital', 'Admin\ParticipantsController@loadrecordcapital')->name('participants.loadrecordcapital');

    Route::get('/participants/loadrecordcoresupports', 'Admin\ParticipantsController@loadrecordcoresupports')->name('participants.loadrecordcoresupports');

    Route::get('/participants/loadrecordplandetailstateditems', 'Admin\ParticipantsController@loadrecordplandetailstateditems')->name('participants.loadrecordplandetailstateditems');

    Route::get('/participants/loadrecordplandetailstateditems/{id}/editrecordplandetails', 'Admin\ParticipantsController@editrecordplandetails')->name('participants.editrecordplandetails');

    Route::post('/participants/deleterecordsupportpurpose', 'Admin\ParticipantsController@deleterecordsupportpurpose')->name('participants.deleterecordsupportpurpose');

    Route::post('/participants/getparticipantserviceprovider', 'Admin\ParticipantsController@getparticipantserviceprovider')->name('participants.getparticipantserviceprovider');

    Route::post('/participants/getserviceproviders', 'Admin\ParticipantsController@getserviceproviders')->name('participants.getserviceproviders');


    //service provider
    Route::get('/participants/loadrecordserviceprovider', 'Admin\ParticipantsController@loadrecordserviceprovider')->name('participants.loadrecordserviceprovider');

    Route::post('/serviceproviders/deleterecordserviceprovider', 'Admin\ParticipantsController@deleterecordserviceprovider')->name('participants.deleterecordserviceprovider');

    Route::post('/serviceproviders/saverecordserviceprovider', 'Admin\ParticipantsController@saverecordserviceprovider')->name('participants.saverecordserviceprovider');

    // support coordinator
    Route::get('/participants/loadrecordsupportcoordinator', 'Admin\ParticipantsController@loadrecordsupportcoordinator')->name('participants.loadrecordsupportcoordinator');

    Route::post('/serviceproviders/deleterecordsupportcoordinator', 'Admin\ParticipantsController@deleterecordsupportcoordinator')->name('participants.deleterecordsupportcoordinator');

    Route::post('/serviceproviders/saverecordsupportcoordinator', 'Admin\ParticipantsController@saverecordsupportcoordinator')->name('participants.saverecordsupportcoordinator');


    //serviceproviders
    Route::get('/serviceproviders', 'Admin\ServiceProvidersController@loadrecords')->name('serviceproviders.loadrecords');


    Route::get('/serviceproviders/{id}/editrecord/', 'Admin\ServiceProvidersController@editrecord')->name('serviceproviders.editrecord');


    Route::post('/serviceproviders', 'Admin\ServiceProvidersController@saverecord')->name('serviceproviders.saverecord');


    Route::post('/serviceproviders/deleterecord', 'Admin\ServiceProvidersController@deleterecord')->name('serviceproviders.deleterecord');

    //supportcoordinators

    Route::get('/supportcoordinators', 'Admin\SupportCoordinatorsController@loadrecords')->name('supportcoordinators.loadrecords');


    Route::get('/supportcoordinators/{id}/editrecord/', 'Admin\SupportCoordinatorsController@editrecord')->name('supportcoordinators.editrecord');


    Route::post('/supportcoordinators', 'Admin\SupportCoordinatorsController@saverecord')->name('supportcoordinators.saverecord');


    Route::post('/supportcoordinators/deleterecord', 'Admin\SupportCoordinatorsController@deleterecord')->name('supportcoordinators.deleterecord');


    //invoices
    Route::get('/invoices', 'Admin\InvoicesController@loadrecords')->name('invoices.loadrecords');

    //reconciliation
    Route::get('/reconciliation', 'Admin\ReconciliationController@loadrecords')->name('reconciliation.loadrecords');

    Route::post('/reconciliation/upload', 'Admin\ReconciliationController@upload')->name('reconciliation.upload');

    Route::post('/reconciliation/hidesuccessfulinvoices', 'Admin\ReconciliationController@hidesuccessfulinvoices')->name('reconciliation.hidesuccessfulinvoices');

    Route::post('/reconciliation/exporttoproda', 'Admin\ReconciliationController@exporttoproda')->name('reconciliation.exporttoproda');

    Route::get('/participants/loadrecordinvoices', 'Admin\ParticipantsController@loadrecordinvoices')->name('participants.loadrecordinvoices');

    Route::get('/invoices/{id}/editrecord/', 'Admin\InvoicesController@editrecord')->name('invoices.editrecord');

    Route::get('/invoices/loadrecordplandetailstateditems', 'Admin\InvoicesController@loadrecordplandetailstateditems')->name('invoices.loadrecordplandetailstateditems');

    Route::post('/invoices/getsinglestateditem', 'Admin\InvoicesController@getsinglestateditem')->name('invoices.getsinglestateditem');

    Route::post('/invoices/updateinvoicestatus', 'Admin\InvoicesController@updateinvoicestatus')->name('invoices.updateinvoicestatus');

    Route::post('/invoices', 'Admin\InvoicesController@saverecord')->name('invoices.saverecord');

    Route::post('/setunitprice', 'Admin\InvoicesController@setunitprice')->name('invoices.setunitprice');


    Route::post('/invoices/deleterecord', 'Admin\InvoicesController@deleterecord')->name('invoices.deleterecord');

    Route::post('/invoices/getstateditems', 'Admin\InvoicesController@getstateditems')->name('invoices.getstateditems');

    Route::post('/invoices/getparticipants', 'Admin\InvoicesController@getparticipants')->name('invoices.getparticipants');

    Route::post('/invoices/getserviceprovider', 'Admin\InvoicesController@getserviceprovider')->name('invoices.getserviceprovider');

    Route::post('/invoices/getgstcode', 'Admin\InvoicesController@getgstcode')->name('invoices.getgstcode');

    Route::post('/invoices/exporttoproda', 'Admin\InvoicesController@exporttoproda')->name('invoices.exporttoproda');

    Route::post('/invoices/exporttoqb', 'Admin\InvoicesController@exporttoqb')->name('invoices.exporttoqb');

    Route::post('/invoices/validateinvoices', 'Admin\InvoicesController@validateinvoices')->name('invoices.validateinvoices');

    Route::post('/invoices/getproviderABN', 'Admin\InvoicesController@getproviderABN')->name('invoices.getproviderABN');

    Route::post('/invoices/getclaimtype', 'Admin\InvoicesController@getclaimtype')->name('invoices.getclaimtype');

    Route::post('/invoices/getcancellationreason', 'Admin\InvoicesController@getcancellationreason')->name('invoices.getcancellationreason');

    Route::get('/invoices/getsupportitems', 'Admin\InvoicesController@getsupportitems')->name('invoices.getsupportitems');

    Route::post('/invoices/hidepaidinvoices', 'Admin\InvoicesController@hidepaidinvoices')->name('invoices.hidepaidinvoices');

    Route::post('/invoices/savepagelength', 'Admin\InvoicesController@savepagelength')->name('invoices.savepagelength');

    Route::post('/participants/upload-document', 'Admin\ParticipantsController@uploadPlanDocument')->name('participants.uploadPlanDocument');

    Route::post('/participants/get-plan-document', 'Admin\ParticipantsController@getPlanDocument')->name('participants.getPlanDocument');

    Route::delete('/participants/delete-plan-document/{id}', 'Admin\ParticipantsController@deletePlanDocument')->name('participants.deletePlanDocument');

    Route::post('/participants/get-plan-latest-document', 'Admin\ParticipantsController@getLatestPlanDocument')->name('participants.getlatestplandocumentajax');
});
//ProviderTypes
// Route::get('/providertypes','Admin\ProviderTypesController@getProviderTypes')->name('providertypes.getprovidertypes');

// Route::get('/providertypes', function(){

//     $providertypes = providertypes::where('id',0)->get();
//     return view('admin.serviceproviders', ["providertypes" => $providertypes]);//->with('providertypes',$providertypes);

// });// Route::get('/providertypes', function(){

//     $providertypes = providertypes::where('id',0)->get();
//     return view('admin.serviceproviders')->with('providertypes',$providertypes);

// });
