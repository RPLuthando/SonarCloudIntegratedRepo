<?php
use App\Mail\TestEmail;
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
if(version_compare(PHP_VERSION, '7.2.0', '>=')) {
    error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
}
Auth::routes(['register' => false]); 
/// login page

Route::post('/login-site', 'LoginController@loginUser'); 
Route::get('/login-site', function () {   
    return view('errors.404');   
    
});
// otp confirmation page
Route::post('/confirmation-view', 'PasswordController@confirmPasscode')
    ->name('confirmPasscode');
/// login email functionality redirect to dashboard after otp submit
Route::get('/loginUser/{email}', 'PasswordController@loginUserViaEmail')
    ->name('loginUser');
 
///add details of user using form in super user
Route::get('/adduserinfo/{type}', 'ProfileListController@adduserinfo')->name('adduserinfo')->middleware(['admin']);
Route::get('/', 'HomeController@dashboard')->middleware(['auth']);

// testing route start
Route::get('/reviewsurveynew', 'HomeController@reviewsurveynew')->middleware(['auth']);

Route::post('/checkedvalue', 'CheckingController@checkboxvalue')->name('checkedvalue')->middleware(['auth']);
Route::get('/allsurveychecked', 'CheckingController@allsurveychecked')->name('allsurveychecked')->middleware(['auth']);
Route::get('/getdatatorun', 'CheckingController@getdatatorun')->name('getdatatorun')->middleware(['auth']);
Route::post('partialsurveydata/{id}', 'CheckingController@partialReviewSurveyNew')->middleware(['auth']);
Route::post('completesurveydata/{id}', 'CheckingController@completeReviewSurvey')->middleware(['auth']);    
Route::post('surveyreviewparametersdata/{id}', 'CheckingController@surveyReviewParameters')->middleware(['auth']);
Route::get('viewsubmission', 'CheckingController@viewSubmission')->name('viewsubmission')->middleware(['auth']); 

// Route for testing mail sendgrid

Route::get('/testmail', function () {
    
    $data = ['message' => 'This is a test!'];
    Mail::to('vijaykashrajput@gmail.com')->send(new TestEmail($data));

    return back();
})->name('testmail');

// Route end after mail

Route::get('getnochange/{id}/entity/{entityId}', array(
    'as' => 'getnochange',
    'uses' => 'CheckingController@getnochange'
));
// Testing route end 
Route::get('surveytestnew/{id}/entity/{entityId}', [
    'as' => 'surveytestnew', 
    'uses' => 'CheckingController@surveyNewType' 
])->middleware(['auth']);

Route::get('surveyreviewnew/{id}/entity/{entityId}', [
    'as' => 'surveyreviewnew', 
    'uses' => 'CheckingController@surveyReviewNew'
])->middleware(['auth']);




Route::group(['middleware' => 'auth'], function () {

  
    Route::get('/superuser-surveyadmin', 'HomeController@superuserSurveyadmin')->name('superuser_surveyadmin');

    
    Route::get('/getSurvey', 'SurveyController@getSurvey');
    Route::get('getSurveyList', 'SurveyController@getSurveyList')->name('getSurveyList');
    Route::post('updateSurveyDeadline', 'SurveyController@updateSurveyDeadLine')->name('updateSurveyDeadline');
    Route::get('/getActive', 'SurveyController@getSurveys');

    Route::get('/create/{accessKey}/{name}', 'SurveyController@addSurvey');
    Route::get('editor', 'SurveyController@editorSurvey'); 
    // Adding route for create survey from superuser dashboard 28 OCT        
    Route::post('/changeName', 'SurveyController@changeSurveyName'); 
    Route::post('expdate', 'SurveyController@expdateSurvey');   
      

    // Routes for Run survey for Responsible users
    Route::get('surveyrun', 'SurveyController@surveyRun');    
    

    
    Route::get('surveyview/{id}/entity/{entityId}', [
                 'as' => 'surveyview', 
                 'uses' => 'SurveyNewController@surveyView'
     ]);
    Route::post('partialsurvey/{id}', 'SurveyNewController@partialSurvey');
    Route::post('completesurvey/{id}', 'SurveyNewController@completeSurvey');    
    Route::post('surveyParameters/{id}', 'SurveyNewController@surveyParameters');

    Route::get('/delete/{id}', 'SurveyController@deleteSurvey'); 
    Route::get('/deletelive/{id}', 'SurveyController@deleteSurveylive');
    
    Route::post('changeJson/{accessKey}', 'SurveyController@changeJson');

    Route::get('/superuser-surveydata', 'HomeController@superuserSurveydata')->name('superuser_surveydata');
    Route::post('/survey-review', 'SurveyQuestionController@surveyreview')->name('surveyreview');
    // all users list in super user dashboard
    Route::get('/userlist/{type?}', 'ProfileListController@usersinfo')->name('userlist')->middleware(['admin']);
    // softdelete user details in super user dashboard
    Route::get('delete/user/{id}', 'ProfileListController@softDeleted')->name('deleteuserlist')->middleware(['admin']);
    // recover user  details in super user dashboard 
    Route::get('recover/user/{id}', 'ProfileListController@recover')->name('recoverUser')->middleware(['admin']);
    //edit user details in super user dashboard
    Route::get('edit/user/{id}', 'ProfileListController@edituserinfo')->name('edituserlist')->middleware(['admin']);
    // edit user details submittion in super user dashboard
    Route::post('editforminfo', 'ProfileListController@editforminfo')->name('editforminfo')->middleware(['admin']);
    // add  entries of user details in super user dashboard
    Route::post('/addinfo', 'ProfileListController@addinfo')->name('addinfo')->middleware(['admin']);
    Route::get('/home', 'HomeController@index')->name('home')->middleware(['admin']);
    Route::get('admin', 'Admin\AdminController@index')->middleware(['admin']);
    Route::get('admin/give-role-permissions', 'Admin\AdminController@getGiveRolePermissions')->middleware(['admin']);
    Route::post('admin/give-role-permissions', 'Admin\AdminController@postGiveRolePermissions')->middleware(['admin']);
    Route::resource('admin/roles', 'Admin\RolesController')->middleware(['admin']);
    Route::resource('admin/permissions', 'Admin\PermissionsController')->middleware(['admin']);
    Route::resource('admin/users', 'Admin\UsersController')->middleware(['admin']);    
    //initial survey view
    // description page survey description in initial phase survey
    
    Route::get('surveydesc/{id}/entity/{entityId}', [
                'as' => 'surveydesc', 
                'uses' => 'SurveyQuestionController@surveydesc' 
    ]);

    Route::post('/saveResult', 'SurveyController@saveResult');


    // form initial survey multistep
    Route::get('/startsurvey/{id?}', 'SurveyQuestionController@startsurvey')->name('startsurvey');
    // initial survey response after form complete on review survey response button
    Route::post('/show-response', 'SurveyQuestionController@show_response');
    Route::get('/show-response', 'SurveyQuestionController@get_show_response');
    // edit form page initial survey url page
    Route::get('/edit-response/{question_id}/{survey_id}', 'SurveyQuestionController@edit_response');
    Route::get('/goto-response/{survey_id}', 'SurveyQuestionController@goto_response');
    /// update edit values in initial survey
    Route::post('/update-response', 'SurveyQuestionController@update_response');
    /// final initial survey submit url
    Route::get('/final-submit/{survey_id}', 'SurveyQuestionController@final_submit_survey');
    // thanks page after initial survey complete
   
    


    //reveiew card
    Route::get('/review-survey-intro', 'ReviewController@review_survey_intro')->name('reviewSurveyIntro');
    Route::get('/review-change/{id}/{a}', 'ReviewController@review_change')->name('reviewChange');
    Route::post('/update-review-results', 'ReviewController@update_review_results');
    Route::get('/review-check/{id}', 'ReviewController@review_check');
    Route::get('/review-perfect/{id}', 'ReviewController@review_check_correct');
    Route::get('/edit-review-list/{question_id}/{survey_id}', 'ReviewController@edit_review_response');
    Route::post('/update-review-list', 'ReviewController@update_response_review');
    Route::get('/thank-you/{ownerId}/{survey_id}', 'ReviewController@thanks_page');

    //remediation survey view
    Route::get('/standard_checklist/{id}', 'QuestionCatController@standardcheck')->name('standardcheck');
    Route::post('/rem-survey', 'QuestionCatController@remsurvey')->name('remsurvey');
    Route::get('/rem-survey', function () {
        return redirect()->back();
    });

    Route::get('/datepicker',function(){
        return view('datepicker');
    });
    Route::post('/rem-survey-step', 'QuestionCatController@remsurveystep');
    Route::get('/review-response/{survey_id}', 'QuestionCatController@reviewResponse');
    // edit form page remediation survey url page
    Route::get('/edit-rem-response/{option_id}/{survey_id}', 'QuestionCatController@edit_rem_response');
    Route::post('/remediation-updated', 'QuestionCatController@remediation_updated');
    Route::get('/remediation-completion/{survey_id}', 'QuestionCatController@remediation_storage');

    
    // management user
    Route::get('/report-active/{id}/{value}', 'InitialReportsController@initialReports')->name('report_active');
    Route::get('/report-initial-results/{id}/{value}', 'InitialReportsController@initialData')->name('report_initial_results');
    Route::get('/report-initial/{id}/{value}', 'InitialReportsController@reportInitial')->name('report_initial');
    Route::get('/report-remediation/{id}/{value}', 'RemediationReportsController@reportRemediation')->name('report_remediation');
    Route::get('/report-initial-individual/{id}/{user_id}', 'RemediationReportsController@reportIndividual')->name('report_initial_individual');
});




// all users list in super user dashboard
Route::get('/entitylist', 'EntityController@index')->middleware(['admin']);
Route::get('/entitylist/create', 'EntityController@create')->middleware(['admin']);
Route::post('/entitylist/store', 'EntityController@store')->name('frameworks.store')->middleware(['admin']);
Route::get('/entitylist/{id}/edit', 'EntityController@edit')->middleware(['admin']);
Route::patch('/entitylist/{id}', 'EntityController@update')->name('entitylist.update')->middleware(['admin']);

Route::get('/entitylist/{id}/delete', 'EntityController@deleteEntity')->middleware(['admin']);

// // all users list in super user dashboard
Route::get('/jsonlist', 'ImportJsonController@index')->middleware(['admin']);
Route::get('/jsonlist/create', 'ImportJsonController@create')->middleware(['admin']);

// Getting Current Score
Route::get('/gettingscore', 'GetScoreController@index')->middleware(['auth']);

//File Upload 
//Route::post('/upload', 'SurveyController@saveFile')->middleware(['auth'])->name('upload');

//Group
Route::post('/group', 'SurveyController@saveGroup')->name('postGroup')->middleware(['auth']);

//DomDie
//Route::get('admin/domdie', 'FillToBeReviewedController@fillToBeReviewed');

//Reset Survey
Route::patch('/resetsurvey', 'ResetsSurveyController@update')->name('resetsurvey.update')->middleware(['admin']);