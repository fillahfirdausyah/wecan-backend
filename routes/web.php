<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['middleware' => 'auth','prefix' => 'api'], function ($router)  {
    $router->get('me', 'AuthController@me');

    // Campaign
    $router->post('/campaign/add', 'CampaignController@makeCampaign');
    $router->get('/campaign/pending', 'CampaignController@getPendingCampaign');
    $router->get('/campaign/accept/{url}', 'CampaignController@acceptCampaign');
    $router->get('/my-campaign', 'CampaignController@myCampaign');

    // Transaction
    $router->post('/campaign/donation/pay/{url}', 'CampaignController@payDonation');
    // $router->get('/campaign/transaction', 'CampaignController@transaction');

    // Comment
    $router->post('/comment', 'CommentController@addComment');

    // Wallet
    $router->get('/wallet', 'WalletController@getWallet');
    $router->post('/wallet/topup', 'WalletController@topUp');
    $router->get('/wallet/make', 'WalletController@makeWallet');

    // Topup
    $router->get('/topup/pending', 'TopupController@getPendingTopup');
    $router->get('/topup/accept/{id}', 'TopupController@acceptTopup');
    $router->get('/topup/transaction/', 'TopupController@getTopupTransaction');

    // Donation
    $router->get('/donation/history', 'DonationController@donationHistory');
});

$router->group(['prefix' => 'api'], function () use ($router) {
    // Auth
    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@login');

    // Campaign
    $router->get('/campaign', 'CampaignController@index');
    $router->get('/campaign/{url}', 'CampaignController@findCampaign');
});
