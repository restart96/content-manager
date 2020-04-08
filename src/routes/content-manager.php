<?php
/**
 * @author SJ
 * @date   2019.12.23
 */

Route::group([
    'prefix' => config('content-manager.route'),
    'middleware' => config('content-manager.middleware'),
    'as' => 'content-manager.',
    'namespace' => 'Restart\ContentManager\App\Http\Controllers',
], function() {

    Route::get('/{group?}/{category?}', [
        'as' => 'index',
        'uses' => 'ContentManagerController@index',
    ]);

    Route::post('/', [
        'as' => 'groups.store',
        'uses' => 'GroupsController@store',
    ]);

    Route::patch('/', [
        'as' => 'groups.update',
        'uses' => 'GroupsController@update',
    ]);

    Route::group(['prefix' => '{group}/{category}/items', 'as' => 'items.'], function() {

        Route::get('/', [
            'as' => 'index',
            'uses' => 'ItemsController@index',
        ]);

        Route::post('/', [
            'as' => 'store',
            'uses' => 'ItemsController@store',
        ]);

        Route::patch('/', [
            'as' => 'updateAll',
            'uses' => 'ItemsController@updateAll',
        ]);

        Route::patch('/{item}', [
            'as' => 'update',
            'uses' => 'ItemsController@update',
        ]);

        Route::delete('/{item}', [
            'as' => 'destroy',
            'uses' => 'ItemsController@destroy',
        ]);
    });
});
