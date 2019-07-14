<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')
	->middleware('api')
	->namespace('Squadron\AppSettings\Http\Controllers\Api')
	->group(function () {
		// guest api routes
		Route::get('/settings', 'AppSettingsController@getList');
		Route::get('/settings/{keys}', 'AppSettingsController@getFilteredList');

		// authenticated api routes
		Route::middleware('auth:api')->group(function () {
			Route::post('/settings', 'AppSettingsController@set');
		});
	});
