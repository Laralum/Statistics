<?php

Route::group([
        'middleware' => [
            'web', 'laralum.base', 'laralum.auth', 'throttle:60,1',
            'can:access,Laralum\Statistics\Models\View',
        ],
        'prefix'    => config('laralum.settings.api_url'),
        'namespace' => 'Laralum\Statistics\Controllers',
        'as'        => 'laralum_api::',
    ], function () {
        Route::post('/statistics/views', 'APIController@getViews')->name('statistics.views');
        Route::post('/statistics/views/unique', 'APIController@getUniqueViews')->name('statistics.uniqueViews');
        Route::post('/statistics/most/os', 'APIController@getMostUsedOs')->name('statistics.mostUsedOs');
        Route::post('/statistics/most/browser', 'APIController@getMostUsedBrowser')->name('statistics.mostUsedBrowser');
        Route::post('/statistics/most/language', 'APIController@getMostUsedLanguage')->name('statistics.mostUsedLanguage');
});