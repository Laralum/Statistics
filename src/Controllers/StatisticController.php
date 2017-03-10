<?php

namespace Laralum\Statistics\Controllers;
use App\Http\Controllers\Controller;
use Laralum\Permissions\Models\Permission;
use Illuminate\Http\Request;
use Laralum\Users\Models\User;
use Laralum\Statistics\Models\View;
use ConsoleTVs\Charts\Facades\Charts;

class StatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $visitsLastWeek = Charts::database(View::all(), 'area', 'highcharts')
          ->title(__('laralum_statistics::general.visits_last_week'))
          ->elementLabel(__('laralum_statistics::general.visits'))
          ->lastByDay(7, true); //true is for fancy output

        $mostUsedBrowsers = Charts::database(View::all(), 'donut', 'highcharts')
          ->title(__('laralum_statistics::general.most_used_browsers'))
          ->elementLabel(__('laralum_statistics::general.browser'))
          ->groupBy('browser'); //true is for fancy output


        $charts = [
            'visits_last_week' => $visitsLastWeek,
            'most_used_browsers' => $mostUsedBrowsers,
        ];

        $views = View::all();

        return view('laralum_statistics::index', ['charts' => $charts, 'views' => $views]);
    }
}
