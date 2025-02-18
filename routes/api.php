<?php

use Illuminate\Http\Request;

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

$api = app('Dingo\Api\Routing\Router');
$api->version('v1', function ($api) {
    $api->group(['namespace' => 'App\Http\Controllers\Api\V1','protected' => true, 'prefix' => 'v1'], function ($api) {

        $api->post('loginUser', 'AuthController@login');
        $api->post('get-new-data', 'AuthController@getnewdata');
        $api->post('get-new-data-download', 'AuthController@getnewdatadownload');

        $api->group(['middleware' => ['jwt.verify']], function ($api_child) {
            
            $api_child->get('accessories', 'UserController@accessories');
            $api_child->get('accessoriestwo', 'UserController@accessoriestwo');
            $api_child->get('accessoriesthree', 'UserController@accessoriesthree');

            $api_child->get('user', 'UserController@getAuthenticatedUser');
            $api_child->get('schooldetails', 'UserController@schooldetails');
            $api_child->get('schooldetailsoffline', 'UserController@schooldetailsoffline');
            $api_child->post('schoolproduct', 'UserController@schoolproduct');
            // Place Order
            $api_child->post('placeOrder', 'UserController@placeOrder');
            $api_child->post('newplaceOrder', 'UserController@newplaceOrder');
            $api_child->get('orderDetail', 'UserController@orderDetail');
            $api_child->post('new-orderDetail', 'UserController@neworderDetail');
            $api_child->post('deleteChildOrder','UserController@deleteChildOrder');
            $api_child->post('deleteOrder','UserController@deleteOrder');
            
            // B2B Place Order
            $api_child->post('B2BPlaceOrder', 'UserController@B2BPlaceOrder');
            $api_child->get('B2BOrderDetail', 'UserController@B2BOrderDetail');
            $api_child->get('B2BInvoice/{id}', 'UserController@B2BInvoice');

            //inventory
            $api_child->get('inventory-view','UserController@inventoryview');
            $api_child->post('inventory-update','UserController@inventoryupdate');

            //users
            $api_child->get('all-shop','AuthController@shop');
            $api_child->get('all-users','AuthController@users');
            $api_child->post('create-users','AuthController@createusers');
            // delete 
            $api_child->post('inventory-update','UserController@inventoryupdate');

            $api_child->get('home','AuthController@home');
              $api_child->post('new-home','AuthController@newhome');

            //logout
            $api_child->get('logout', 'UserController@logout');
            $api_child->get('getrawmaterial', 'UserController@getrawmaterial');
        });
    });
});
