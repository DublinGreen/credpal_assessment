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

// Route::get('sendbasicemail', 'MailController@basic_email');
// Route::get('sendhtmlemail', 'MailController@html_email');
// Route::get('sendattachmentemail', 'MailController@attachment_email');

Route::get('send_test_email', function(){
	Mail::raw('Sending emails with Mailgun and Laravel is easy!', function($message)
	{
		$message->to('greendublin007@gmail.com');
	});
});

$router->post('auth/createUser', ['uses' => 'UserController@create']);

$router->group(['middleware' => 'jwt.auth', 'prefix' => 'apiv1'], function () use ($router) {
    $router->get('users', ['uses' => 'UserController@getAllUsers']);

    $router->get('userByID/{id}', ['uses' => 'UserController@getUser']);

    $router->post('getUserByEmail', ['uses' => 'UserController@getUserByEmail']);

    $router->put('updateUser/{id}', ['uses' => 'UserController@update']);

    $router->post("uploadDocument",['uses' => 'UserDocumentController@uploadDocument']);
});


Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
 });
