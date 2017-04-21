<?php

namespace Laralum\Statistics\Controllers;

use App\Http\Controllers\Controller;
use ConsoleTVs\Charts\Facades\Charts;
use Laralum\Statistics\Models\View;

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

        $uniqueVisitorsLastWeek = Charts::database(View::all()->unique('ip'), 'area', 'highcharts')
          ->title(__('laralum_statistics::general.unique_visitors_last_week'))
          ->elementLabel(__('laralum_statistics::general.unique_visitors'))
          ->lastByDay(7, true); //true is for fancy output

        $mostUsedBrowsers = Charts::database(View::all(), 'donut', 'highcharts')
          ->title(__('laralum_statistics::general.most_used_browsers'))
          ->elementLabel(__('laralum_statistics::general.browser'))
          ->groupBy('browser')
          ->colors(['#F44336', '#3F51B5', '#4CAF50', '#FFC107', '#2196F3', '#009688', '#673AB7', '#795548']);

        $mostUsedOs = Charts::database(View::all(), 'donut', 'highcharts')
          ->title(__('laralum_statistics::general.most_used_os'))
          ->elementLabel(__('laralum_statistics::general.os'))
          ->groupBy('os')
          ->colors(['#F44336', '#3F51B5', '#4CAF50', '#FFC107', '#2196F3', '#009688', '#673AB7', '#795548']);

        $views = View::all();

        return view('laralum_statistics::index', [
            'views'                  => $views,
            'viewsLastWeek'          => $viewsLastWeek,
            'uniqueVisitorsLastWeek' => $uniqueVisitorsLastWeek,
            'mostUsedBrowsers'       => $mostUsedBrowsers,
            'mostUsedOs'             => $mostUsedOs,
        ]);
    }

    public function restartConfirmation()
    {
        $this->authorize('restart', View::class);
        return view('laralum::pages.confirmation', [
            'method'  => 'DELETE',
            'message' => __('laralum_statistics::general.clear_all'),
            'action'  => route('laralum::statistics.restart'),
        ]);
    }

    public function restart()
    {
        $this->authorize('restart', View::class);
        View::all()->each(function ($view) {
            $view->delete();
        });
        return redirect()->route('laralum::statistics.index')->with('success', __('laralum_statistics::general.statistics_restarted'));
    }
}
