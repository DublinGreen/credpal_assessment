<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post(
    'auth/login',
    [
        'uses' => 'AuthController@authenticate',
    ]
);


$router->get('auth/confirmEmail/{companyName}/{linkKey}', ['uses' => 'UserController@confirmEmail']);

$router->get('fetchUserDocument/{key}', ['uses' => 'UserDocumentController@fetchUserDocument']);



// Route::get('sendbasicemail', 'MailController@basic_email');
// Route::get('sendbasicemail', 'MailController@basic_email');
// Route::get('sendhtmlemail', 'MailController@html_email');
// Route::get('sendattachmentemail', 'MailController@attachment_email');

// Route::get('send_test_email', function(){
// 	Mail::raw('Sending emails with Mailgun and Laravel is easy!', function($message)
// 	{
// 		$message->to('greendublin007@gmail.com');
// 	});
// });

$router->post('auth/createUser', ['uses' => 'UserController@create']);

$router->post('auth/getIDByReferralCodes', ['uses' => 'UserController@getIDByReferralCodes']);

$router->group(['middleware' => 'jwt.auth', 'prefix' => 'apiv1'], function () use ($router) {
    $router->get('users', ['uses' => 'UserController@getAllUsers']);

    $router->get('userByID/{id}', ['uses' => 'UserController@getUser']);

    $router->get('getAccountByAccountNumber/{number}', ['uses' => 'AccountController@getAccountByAccountNumber']);

    $router->post('getUserByEmail', ['uses' => 'UserController@getUserByEmail']);

    $router->post('getUserByMobile', ['uses' => 'UserController@getUserByMobile']);

    $router->get('getUserByName/{name}', ['uses' => 'UserController@getUserByName']);

    $router->put('updateUser/{id}', ['uses' => 'UserController@update']);

    $router->post("updateCompanyLogo", ['uses' => 'UserController@updateCompanyLogo']);

    $router->post("uploadDocument", ['uses' => 'UserDocumentController@uploadDocument']);

    $router->get('getUserDocuments/{id}', ['uses' => 'UserDocumentController@getUserDocuments']);

    $router->post("updateUserDocument", ['uses' => 'UserDocumentController@updateUserDocument']);

    $router->post("sendDocumentViaEmail", ['uses' => 'UserDocumentController@sendDocumentViaEmail']);

    $router->get('fetchUserDocument/{key}', ['uses' => 'UserDocumentController@fetchUserDocument']);

    $router->get('getActiveDocumentType', ['uses' => 'UserDocumentTypeController@getAllActiveDocumentType']);

    $router->get('getDocumentTypeByID/{id}', ['uses' => 'UserDocumentTypeController@getDocumentByID']);

    $router->get('getDocumentTypeByName/{name}', ['uses' => 'UserDocumentTypeController@getDocumentByName']);

    $router->get('getDocumentExpirationByName/{name}', ['uses' => 'UserDocumentExpirationController@getDocumentExpirationByName']);

    $router->get('getAllActiveDocumentExpiration', ['uses' => 'UserDocumentExpirationController@getAllActiveDocumentExpiration']);

    $router->get('getDocumentExpirationByID/{id}', ['uses' => 'UserDocumentExpirationController@getDocumentExpirationByID']);

    $router->get('getAllActiveTrainingWithFiles', ['uses' => 'TrainingController@getAllActiveTrainingWithFiles']);

    $router->get('getAllTraining', ['uses' => 'TrainingController@getAllTraining']);

    $router->get('getTrainingByID/{id}', ['uses' => 'TrainingController@getTrainingByID']);
});


Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});
