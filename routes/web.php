<?php

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
    return view('welcome');
});

####################
### pagamentos #####
####################

Route::get('/index', 'PayPalController@index');

Route::post('paypal', 'PayPalController@payWithPaypal');

Route::get('status', 'PayPalController@getPaymentStatus');

Route::get('/recuperaPagamento', 'PayPalController@recuperaPagamento');

Route::get('/recuperaListaPagamentos', 'PayPalController@recuperaListaPagamentos');

Route::get('/recuperaInformacoesVenda', 'PayPalController@recuperaInformacoesVenda');

Route::get('/refund', 'PayPalController@refund');

######################
### Notificações #####
######################

Route::get('/listWebHooksEvents', 'PayPalController@listWebHooksEvents');

Route::get('/createWebhook', 'PayPalController@createWebhook');

Route::get('/getWebhook', 'PayPalController@getWebhook');

Route::get('/listWebhooks', 'PayPalController@listWebhooks');

Route::get('/updateWebhook', 'PayPalController@updateWebhook');

Route::get('/deleteWebhook', 'PayPalController@deleteWebhook');

Route::get('/deleteAllWebhook', 'PayPalController@deleteAllWebhook');

Route::get('/getWebhookEvents', 'PayPalController@getWebhookEvents');

//OBS: como essa é uma rota post não acessada por um form então podemos remover a verificação
//de csrf adicionando uma exceção no middleware VerifyCsrfToken indicando esta rota
Route::post('/eventListener', 'PayPalController@eventListener');

##################
### Webhooks #####
##################

Route::post('/testDiscordWebhook', 'PayPalController@testDiscordWebhook')->name('testDiscordWebhook');

Route::post('/testSlackWebhook', 'PayPalController@testSlackWebhook')->name('testSlackWebhook');

Route::get('/index2', 'PayPalController@index2');




