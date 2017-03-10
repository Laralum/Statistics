<?php

Route::group([
        'middleware' => [
            'web', 'laralum.base', 'laralum.auth',
            'can:access,Laralum\Statistics\Models\View',
        ],
        'prefix' => config('laralum.settings.base_url'),
        'namespace' => 'Laralum\Statistics\Controllers',
        'as' => 'laralum::'
    ], function () {
        Route::get('statistics', 'StatisticController@index')->name('statistics.index');
});
