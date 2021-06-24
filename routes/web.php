<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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

Auth::routes();

Route::get('/', 'HomeController@index')->name('home')->middleware('auth');

Route::group(['middleware' => 'auth'], function () {

    Route::resources([
      //  'animalbites' =>'AnimalbiteController',
        'users' => 'UserController',
        'providers' => 'ProviderController',
        // 'billing'   => 'BillingController',
        'inventory/products' => 'ProductController',
        'clients' => 'ClientController',
        'inventory/categories' => 'ProductCategoryController',
        'transactions/transfer' => 'TransferController',
        'methods' => 'MethodController',
        'admitting/inpatients'=> 'InpatientController',
        'wards' => 'WardController',
        'dietetics' => 'DieteticsController',
        'reference' => 'ReferenceController',
        'emergencyroom' => 'ErpatientController',
        'doctors/patientlist'=>'DoctorsController',
        'referralsfrom' => 'HrefromController',
        'roles' => 'RoleController',
    ]);

  //  Route::resource('/userData','DieteticsController');
    Route::get('admission/create', ['as' => 'referralsfrom.create', 'uses' => 'HrefromController@create']);
    Route::resource('transactions', 'TransactionController')->except(['create', 'show']);
    Route::get('transactions/stats/{year?}/{month?}/{day?}', ['as' => 'transactions.stats', 'uses' => 'TransactionController@stats']);
    Route::get('transactions/{type}', ['as' => 'transactions.type', 'uses' => 'TransactionController@type']);
    Route::get('transactions/{type}/create', ['as' => 'transactions.create', 'uses' => 'TransactionController@create']);
    Route::get('transactions/{transaction}/edit', ['as' => 'transactions.edit', 'uses' => 'TransactionController@edit']);

    Route::get('inventory/stats/{year?}/{month?}/{day?}', ['as' => 'inventory.stats', 'uses' => 'InventoryController@stats']);
    Route::resource('inventory/receipts', 'ReceiptController')->except(['edit', 'update']);
    Route::get('inventory/receipts/{receipt}/finalize', ['as' => 'receipts.finalize', 'uses' => 'ReceiptController@finalize']);
    Route::get('inventory/receipts/{receipt}/product/add', ['as' => 'receipts.product.add', 'uses' => 'ReceiptController@addproduct']);
    Route::get('inventory/receipts/{receipt}/product/{receivedproduct}/edit', ['as' => 'receipts.product.edit', 'uses' => 'ReceiptController@editproduct']);
    Route::post('inventory/receipts/{receipt}/product', ['as' => 'receipts.product.store', 'uses' => 'ReceiptController@storeproduct']);
    Route::match(['put', 'patch'], 'inventory/receipts/{receipt}/product/{receivedproduct}', ['as' => 'receipts.product.update', 'uses' => 'ReceiptController@updateproduct']);
    Route::delete('inventory/receipts/{receipt}/product/{receivedproduct}', ['as' => 'receipts.product.destroy', 'uses' => 'ReceiptController@destroyproduct']);

    Route::resource('sales', 'SaleController')->except(['edit', 'update']);
    Route::get('sales/{sale}/finalize', ['as' => 'sales.finalize', 'uses' => 'SaleController@finalize']);
    Route::get('sales/{sale}/product/add', ['as' => 'sales.product.add', 'uses' => 'SaleController@addproduct']);
    Route::get('sales/{sale}/product/{soldproduct}/edit', ['as' => 'sales.product.edit', 'uses' => 'SaleController@editproduct']);
    Route::post('sales/{sale}/product', ['as' => 'sales.product.store', 'uses' => 'SaleController@storeproduct']);
    Route::match(['put', 'patch'], 'sales/{sale}/product/{soldproduct}', ['as' => 'sales.product.update', 'uses' => 'SaleController@updateproduct']);
    Route::delete('sales/{sale}/product/{soldproduct}', ['as' => 'sales.product.destroy', 'uses' => 'SaleController@destroyproduct']);



//Animalbites ER/OPD
        Route::get('animalbites',[
            'as' => 'animalbites',
            'uses' => 'AnimalbiteController@index',
        ]);

        Route::get('animalbites/patientlist/{date?}',[
            'as' => 'animalbites.get_patientlist',
            'uses' => 'AnimalbiteController@get_patientlist',
        ]);


//Admitting
    Route::get('admitting/dailyadmissions/{month?}{year?}',[
    'as' => 'admitting.dailyadmissions',
    'uses' => 'InpatientController@dailyadmissions',
]);

Route::get('admission/add/{id?}',[
    'as' => 'admissions.add',
    'uses' => 'InpatientController@add',
]);

Route::get('admission/edit/{id?}',[
    'as' => 'admission.edit',
    'uses' => 'InpatientController@edit',
]);

Route::get('admission/dichargeinfo/{id?}',[
    'as' => 'admission.dischargeinfo',
    'uses' => 'InpatientController@getdischargeinfo',
]);


Route::get('opd/dichargeinfo/{id?}',[
    'as' => 'opd.dischargeinfo',
    'uses' => 'OutpatientController@getdischargeinfo',
]);

Route::GET('admission/update/{id?}',[
    'as' => 'admission.update',
    'uses' => 'InpatientController@update',
]);

Route::GET('admission/discharge/{id?}',[
    'as' => 'admission.discharge',
    'uses' => 'InpatientController@discharge'
]);
Route::GET('canceladmission',[
    'as' => 'admitting.canceladmission',
    'uses' => 'InpatientController@canceladmission',
]);


// Route::GET('admission/admissiondoctors/{id?}',[
//     'as' => 'admission.admissiondoctors',
//     'uses' => 'InpatientController@admissiondoctors',
// ]);


// Route::GET('admitting/coversheet/{id?}',[
    Route::GET('admitting/coversheet/{id?}',[
    'as' => 'admitting.coversheet',
    'uses' => 'InpatientController@coversheet_pdf',
]);

Route::GET('admission/admissionslip/{id?}',[
    'as' => 'admission.admissionslip',
    'uses' => 'InpatientController@admissionslip_pdf',
]);

Route::GET('admitting/clinicalabstract/{id?}',[
    'as' => 'admitting.clinicalabstract',
    'uses' => 'InpatientController@clinicalabstract_pdf',
]);

Route::GET('admission/print_inpatientslist',[
    'as' => 'admission.print_inpatientslist',
    'uses' => 'InpatientController@inpatientslist_pdf',
]);

Route::GET('admission/admissiondoctors/{id?}',[
    'as' => 'admission.admissiondoctors',
    'uses' => 'PatientController@patientdoctors',
]);

 Route::GET('admission/admissionrooms/{id?}',[
    // Route::GET('admission/admissionrooms',[
    'as' => 'admission.admissionrooms',
    'uses' => 'InpatientController@Patient_rooms',
]);
Route::GET('admission/rooms',[
    // Route::GET('admission/admissionrooms',[
    'as' => 'patient.admissionrooms',
    'uses' => 'InpatientController@get_PatientRooms',
]);


 //Route::GET('admission/add_doctor/{id?}',[
Route::GET('admission/add_doctor/{id?}',[
    'as' => 'admission.add_doctor',
    'uses' => 'HadmconsController@save_doctor',
]);


//Admission Referral From
Route::get('admisson/referralsfrom', [ 'as' => 'admisson.referralsfrom', 'uses' => 'HrefromController@index']);

//
//Billing Cashiering

Route::get('billing/fordischarge/{id?}',
[
    'as'=>'getpatient.fordischarge',
    'uses' => 'BillingController@index'
]);


Route::get('billing/soa/{id?}',
        [
            'as'=>'billing.soa',
            'uses' => 'BillingController@soa'
        ]);
Route::get('/roomcharges',[
    'as' => 'getPatient.roomcharges',
    'uses' => 'BillingController@get_roomcharges'
]);
Route::get('/itemscharges',[
    'as' => 'getPatient.itemscharges',
    'uses' => 'BillingController@get_itemscharges'
]);
Route::get('/profservcharges',[
    'as' => 'getPatient.profservcharges',
    'uses' => 'BillingController@get_profservcharges'
]);

Route::get('/drugmedscharges',[
    'as' => 'getPatient.drugmedscharges',
    'uses' => 'BillingController@get_drugmedscharges'
]);

Route::get('/drugmedsreturn',[
    'as' => 'getPatient.drugmedsreturn',
    'uses' => 'BillingController@get_drugmedsreturn'
]);






Route::get('billing/statement',
        [
            'as'=>'billing.statement',
            'uses' => 'BillingController@statement'
        ]);

//`
Route::get('cashiering/summaryreport',[
    'as' => 'cashiering.rptsummary',
    'úses' => 'CashieringController@rptsummary',
]);

//--end billing

Route::get('admitting/foradmission',[
    'as' => 'admitting.foradmission',
    'uses' => 'ERPatientController@foradmission',
]);

//WARDS
    Route::get('wards/{id}',[
        'as' => 'wards.index',
        'uses' => 'WardController@index',
    ]);

    // Route::get('emergencyroom/{id}',[
    //     'as' => 'emergencyroom.index',
    //     'uses' => 'ErpatientController@index',
    // ]);

    Route::GET('anaimalbitelog/edit/{id?}',[
        'as' => 'animalbitelog.edit',
        'uses' => 'AnimalBiteController@animalbitelog_edit',
    ]);

    Route::GET('anaimalbite/form/{id?}',[
        'as' => 'animalbite.form',
        'uses' => 'AnimalBiteController@animalbite_pdf',
    ]);


    Route::get('erpatient/discharge','ErpatientController@discharge')->name('erpatient.discharge');

      Route::Get('erdeaths/{date1?}{date2?}', [
        'uses' => 'ErpatientController@er_deaths',
        'as'   => 'erdeaths',
      ]);

      Route::get('admitting/inpatients/{id}',[
        'as' => 'inpatients.index',
        'uses' => 'InpatientController@index',
    ]);


    // Route::get('admission/update/{id?}',[
    //     'as' => 'admission.update',
    //     'uses' => 'InpatientController@update',
    // ]);


    //Dietetics Routes
    Route::get('/dietetics/{id}',[
        'as' => 'dietetics.show',
        'uses' => 'DieteticsController@index',
    ]);

    Route::get('/dietetics/report/{id?}',[
        'as' => 'dietetics.rptDietlist',
        'uses' => 'DieteticsController@rptDietlist',
    ]);




    //Patient Master
    Route::get('patient/create','PatientController@create')->name('patient.create');



    Route::get('patient/discharge/{id?}','InpatientController@patient_discharge')->name('patient.discharge');

    Route::get('patients/dailydischarges', 'InpatientController@daily_discharges')->name('patient.dailydischarges');;

    Route::Get('patients/dailydischarges/{id?}', [
        'uses' => 'InpatientController@daily_discharges',
        'as'   => 'dailydischarges',
      ]);

    Route::get('patient/charges/{id?}',[
        'as'=>'patient.charges',
            'uses' => 'PatientController@patientcharges'
    ]);

    Route::get('patient/doctors/{id}',[
        'as'=>'patient.doctors',
            'uses' => 'PatientController@patientdoctors'
    ]);

    Route::get('doctor/getdoctorsorder/{id}',[
        'as'=>'patient.doctorsorder',
            'uses' => 'PatientController@patientdoctorsorder'
    ]);


    Route::get('doctor/getdoctor',[
        'as'=>'doctor.get_doctors',
            'uses' => 'DoctorsController@get_doctorsbytype'
    ]);
    Route::get('doctor/getdoctorbyservicetype',[
        'as'=>'ajax.get_doctorsbyservicetype',
            'uses' => 'DoctorsController@get_doctorsbyservicetype'
    ]);

    Route::get('patient/dietorder',[
        'as'=>'patient.dietorder',
             'uses' => 'DoctorsController@dietorder'
    ]);

    Route::get('dietorder/update/{id?}',[
        'as'=>'dietorder.update',
        'uses' => 'DieteticsController@update'
    ]);

    Route::POST('dietorder/diet_add/{id?}',[
        'as'=>'dietorder.diet_add',
        'uses' => 'DieteticsController@diet_add'
    ]);

    Route::GET('dietorder/destroy/{id?}',[
        'as'=>'dietorder.destroy',
        'uses' => 'DieteticsController@destroy'
    ]);

    Route::get('/getPatientdiet',[
            'as' => 'getPatient.diet',
            'uses' => 'DieteticsController@get_dietorder'
        ]);

        Route::get('/getDoctorsorder/diet',
        [
            'as' => 'editdietorder',
            'uses' => 'DieteticsController@dietorder_edit'
        ]);


        Route::get('/getPatientmedication',
        [
            'as' => 'getPatient.medication',
            'uses' => 'PatientController@get_medicationorder'
        ]);
        Route::get('/getPatientlaboratories',
        [
            'as' => 'getPatient.laboratories',
            'uses' => 'PatientController@get_laboratoryorder'
        ]);

        Route::get('/getradiologyorder',
        [
            'as' => 'getPatient.radiologyorder',
            'uses' => 'PatientController@get_radiologyorder'
        ]);

        Route::get('/getdischargeorder',
        [
            'as' => 'getPatient.dischargeorder',
            'uses' => 'PatientController@get_fordischargeorder'
        ]);


//Emergencyroom Route


//Medical Records Route
Route::get('medicalrecords/index',
[
    'as'=>'medicalrecords.index',
    'uses' => 'MedrecController@index'
]);
Route::get('medicalrecords/show/{id?}',
[
    'as'=>'medicalrecords.show',
    'uses' => 'MedrecController@show'
]);

Route::get('medicalrecords/codediagnosis',
[
    'as'=>'medicalrecords.codediagnosis',
    'uses' => 'MedrecController@code_diagnosis'
]);




        //Philhealth Module
        Route::get('patient/cf4/show/{id?}',
        [
          'as'=>'cf4.show',
          'uses' => 'CF4Controller@index'
        ]);

        Route::get('phic/cf4',
        [
            'as'=>'phic.cf4',
            'uses' => 'CF4Controller@index'
        ]);

        Route::get('/signsymptoms_update','CF4Controller@saveSignsSymptoms')->name('cf4.signsymptoms_update');
        Route::get('/examination_update','CF4Controller@saveExamination')->name('cf4.examination_update');
        Route::get('/histillness_update','CF4Controller@saveHistoryIllness')->name('cf4.histillness_update');
        Route::get('/admission_update','CF4Controller@saveAdmissionComplaint')->name('cf4.achob_update');

      //  Route::get('/drugmeds_update','CF4Drugsmeds@saveDrugsMeds')->name('cf4.drugmeds_update');
        Route::post('/drugmeds_update','CF4Drugsmeds@saveDrugsMeds')->name('drugmeds_update');

        Route::get('phic/norasys/{id?}',
        [
            'as'=>'phic.norasys',
            'uses' => 'PhilhealthController@norasys'
        ]);
        Route::get('norasys/summary',
        [
            'as'=>'getnorasys.summary',
            'uses' => 'PhilhealthController@norasys_summary'
        ]);



        Route::get('phic/norasys_report/{id?}',
        [
            'as'=>'phic.norasys_report',
            'uses' => 'PhilhealthController@norasys_report'
        ]);

        Route::get('phic/mmhreport/{id?}',
        [
            'as'=>'phic.mmhr',
            'uses' => 'PhilhealthController@mmh_reporting'
        ]);


        Route::get('/live_search/patient', 'PatientController@action')->name('live_search.action');
    //    Route::get('/getPatienthistory/patient', 'PatientController@getPatient_history')->name('getPatient.history');
    Route::get('/getcharges/items', 'ItemController@getcharge_items')->name('getcharges.items');
    Route::get('clients/{client}/transactions/add', ['as' => 'clients.transactions.add', 'uses' => 'ClientController@addtransaction']);

    Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
    Route::match(['put', 'patch'], 'profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
    Route::match(['put', 'patch'], 'profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);


    //AJAX ROUTES
    Route::get('province/city',
    [
        'as'=>'ajax.get_provincebycitycode',
        'uses' => 'PatientController@get_provincebycitycode'
    ]);

    Route::get('province/city',
    [
        'as'=>'ajax.get_Itemsmedication',
        'uses' => 'ItemController@get_Itemsmedication'
    ]);
    //


});

    //Administrator Route
    Route::get('/system/cancelencounter', 'SystemController@cancel_encounter')->name('system.cancelencounter');

   Route::get('/getPatienthistory/patient', 'PatientController@getPatient_history')->name('getPatient.history');
   //Doctors Module


   Route::get('progressnotes/{id}', [ 'as'=>'doctors.show',      'uses' => 'DoctorsController@show']  );
   Route::get('patient/doctors/order/{id}',
   [
     'as'=>'doctors.order',
     'uses' => 'DoctorsController@doctororders'
   ]);
   Route::get('/doctors/radiologyresult/{id}', [ 'as'=>'doctors.radiologyresult',      'uses' => 'PatientController@radiologyresult']  );

   Route::group(['middleware' => 'auth'], function () {
    Route::get('icons', ['as' => 'pages.icons', 'uses' => 'PageController@icons']);
    Route::get('notifications', ['as' => 'pages.notifications', 'uses' => 'PageController@notifications']);
    Route::get('tables', ['as' => 'pages.tables', 'uses' => 'PageController@tables']);
    Route::get('typography', ['as' => 'pages.typography', 'uses' => 'PageController@typography']);
});
