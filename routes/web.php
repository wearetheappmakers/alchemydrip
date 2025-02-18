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
    return view('auth.login');
});
Route::get('/login', function () {
    return view('auth.login');
});
// Auth::routes();
Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => 'auth:admin'],function(){

    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@dashboard')->name('home');
    Route::get('/home/delete-multiple', 'HomeController@deleteMultiple')->name('home.delete-multiple');
    Route::get('/home/change-multiple-status', 'HomeController@changeMultipleStatus')->name('home.change-multiple-status');


// Route::group(['prefix' => 'user', 'as' => 'user.'],function(){
// 		Route::resource('/user','UserController')->name('user.index');

// 	});


    Route::resource('shop','ShopController');
    Route::resource('user','UserController');
    Route::resource('school','SchoolController');
    Route::resource('product','ProductController');
    Route::resource('po','PoController');
    Route::any('report/employee','HomeController@reportemployee')->name('report.employee');
    Route::any('report/employee/get-data','HomeController@reportemployeegetdata')->name('report.employee.getdata');
    Route::get('/po/delete/{id}','PoController@delete_clone')->name('po.delete_clone');

    Route::get('/inventory','InventoryController@index')->name('inventory.index');
    Route::any('/inventory/minimum','InventoryController@indexminimum')->name('inventory.indexminimum');
    Route::post('/inventory/update','InventoryController@update')->name('inventory.update');

// 
    Route::get('/sampleDownload','ProductController@sampleDownload')->name('sampleDownload');
    Route::get('/productExport','ProductController@productExport')->name('productExport');
    Route::post('/productsImport','ProductController@productsImport')->name('productsImport');

    Route::any('/ledger/index','ProductController@ledgerindex')->name('ledger.index');
    Route::any('/ledger/index/so','ProductController@ledgerindexso')->name('ledger.index.so');
    Route::any('/ledger/index/so/data','ProductController@ledgerindexsodata')->name('ledger.index.so.data');
    Route::any('/ledger/index/so/data/pdfs','ProductController@ledgerindexsopdfdata')->name('ledger.index.so.data.pdf');
    Route::any('/ledger/index/new','ProductController@ledgerindexnew')->name('ledger.index.new');
    Route::any('/ledger/pdf','ProductController@ledgerpdf')->name('ledger.pdf');
// 
    Route::resource('so', 'SoController');
    Route::get('so/status/{status_id}','SoController@index')->name('so.index.status');
    Route::get('/so/delete/{id}','SoController@delete')->name('so.delete');

    Route::post('/get-product','SoController@getproduct')->name('get.product');
    Route::post('/get-price','SoController@getprice')->name('get.price');
    Route::post('/get-size','SoController@getsize')->name('get.size');
    Route::post('/get-gender','SoController@getgender')->name('get.gender');


    Route::resource('size','SizeController');
    Route::resource('gender','GenderController');
    Route::post('general-product', 'ProductController@general_update')->name('product.general_update');
    Route::post('size-product', 'ProductController@size_update')->name('product.size_update');
    Route::post('gender-product', 'ProductController@gender_update')->name('product.gender_update');

    Route::any('inventory-product', 'ProductController@inventory_update')->name('product.inventory_update');
    Route::any('price-product', 'ProductController@price_update')->name('product.price_update');

    Route::resource('orderstatus','OrderStatusController');
    Route::any('/get-order-list', 'OrderController@index')->name('order.index');
    Route::post('order/status','OrderController@status')->name('order.get.approval');

    Route::get('/delete/{id}','SoController@deleteclone')->name('delete.clone');


    Route::resource('b2b','B2bController');
    Route::get('/b2b/delete/{id}','B2bController@delete')->name('b2b.delete');
    Route::get('b2b/status/{status_id}','B2bController@index')->name('b2b.index.status');

    Route::resource('otherproduct','OtherproductController');
    Route::resource('otherproducttwo','OtherProductTwoController');
    Route::resource('otherproductthree','OtherProductThreeController');
    Route::post('/get-other-price','SoController@getotherprice')->name('get.other.price');

    Route::post('/b2b/invoice/{id}', 'InvoiceController@pdf')->name('b2b.invoice');
    // Route::post('/b2b/invoice/store/{id}', 'InvoiceController@store')->name('invoice.store');
    // Route::post('/invoice/pdf', 'InvoiceController@pdf')->name('pdf.invoice');

    Route::resource('setting', 'SettingController');    
    Route::resource('rawmaterial', 'RawmaterialController');   

});

