<?php

Route::get('catalog/hello', function () {
    return Catalog::hello();
});

Route::get('catalog/view', function () {
    return view('liteweb-catalog::index');
});

Route::group([/*'middleware' => 'auth', */'prefix' => 'catalog', 'namespace' => 'Liteweb\Catalog\Http\Controllers'], function()
{
    Route::get('/', 'CatalogController@index');
    Route::get('/fix', 'CatalogController@fix');
    Route::get('/getcategoriesselect', 'CatalogController@getCategoriesSelect');
    Route::get('/getdealtypes/{id}', 'CatalogController@getDealtypes');
    Route::get('/test', 'CatalogController@test');
    Route::get('/category/getparams/{id}', 'CatalogController@category');
    Route::post('/delete/{catalog}/{param?}', 'CatalogController@delete');
    Route::post('/edit/{catalog}/{param?}', 'CatalogController@edit');
    Route::post('/addcategory', 'CatalogController@addcategory');
    // Route::post('/delete/{catalog}/{param?}', function() {
    //     return response()->json(['status' => 200, 'messages' => Request::all()]);
    // });
});
