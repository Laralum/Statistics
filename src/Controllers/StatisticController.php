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
        $viewsLastWeek = Charts::database(View::all(), 'area', 'highcharts')
          ->title(__('laralum_statistics::general.views_last_week'))
          ->elementLabel(__('laralum_statistics::general.views'))
          ->lastByDay(7, true); //true is for fancy output

        $uniqueVisitorsLastWeek = Charts::database(View::all()->unique('ip') , 'area', 'highcharts')
          ->title(__('laralum_statistics::general.unique_visitors_last_week'))
          ->elementLabel(__('laralum_statistics::general.unique_visitors'))
          ->lastByDay(7, true); //true is for fancy output

        $mostUsedBrowsers = Charts::database(View::all(), 'donut', 'highcharts')
          ->title(__('laralum_statistics::general.most_used_browsers'))
          ->elementLabel(__('laralum_statistics::general.browser'))
          ->groupBy('browser'); //true is for fancy output

        $mostUsedOs = Charts::database(View::all(), 'donut', 'highcharts')
          ->title(__('laralum_statistics::general.most_used_os'))
          ->elementLabel(__('laralum_statistics::general.os'))
          ->groupBy('os'); //true is for fancy output


        $views = View::all();

        return view('laralum_statistics::index', [
            'views' => $views,
            'viewsLastWeek' => $viewsLastWeek,
            'uniqueVisitorsLastWeek' => $uniqueVisitorsLastWeek,
            'mostUsedBrowsers' => $mostUsedBrowsers,
            'mostUsedOs' => $mostUsedOs,
        ]);
    }
}
