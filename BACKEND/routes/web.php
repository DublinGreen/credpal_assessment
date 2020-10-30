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


//$router->get('auth/confirmEmail/{companyName}/{linkKey}', ['uses' => 'UserController@confirmEmail']);

$router->post('auth/createUser', ['uses' => 'UserController@create']);

$router->post('auth/getIDByReferralCodes', ['uses' => 'UserController@getIDByReferralCodes']);

$router->group(['middleware' => 'jwt.auth', 'prefix' => 'apiv1'], function () use ($router) {
    $router->get('users', ['uses' => 'UserController@getAllUsers']);

    $router->get('userByID/{id}', ['uses' => 'UserController@getUser']);

    $router->get('getAccountByAccountNumber/{number}', ['uses' => 'AccountController@getAccountByAccountNumber']);

    $router->post('getUserByEmail', ['uses' => 'UserController@getUserByEmail']);

    $router->post('getUserByMobile', ['uses' => 'UserController@getUserByMobile']);

    $router->post('getWalletBalance', ['uses' => 'WalletController@getBalance']);

    $router->post('transferFunds', ['uses' => 'WalletController@transfer']);

    $router->post('getAccountNumberByUserID', ['uses' => 'AccountController@getAccountNumberByUserID']);

    $router->put('updateUser/{id}', ['uses' => 'UserController@update']);

    $router->get('getUserByName/{name}', ['uses' => 'UserController@getUserByName']);
});


Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});
