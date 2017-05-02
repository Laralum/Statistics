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
        $labels = []; $normal_views = []; $unique_views = [];
        $all_views = View::all();

        for ($i = 0; $i < 7; $i++) {
            $time = strtotime("- $i days");
            $nv = View::whereDate('created_at', date('Y-m-d', $time))->get();
            array_push($labels, date('l dS M, Y', $time));
            array_push($normal_views, $nv->count());
            array_push($unique_views, $nv->unique('ip')->count());
        }

        $latest_views_chart = Charts::multi('bar', 'highcharts')
                                ->title(' ')->elementLabel(__('laralum_statistics::general.views'))
                                ->labels(array_reverse($labels))
                                ->dataset(__('laralum_statistics::general.views'), array_reverse($normal_views))
                                ->dataset(__('laralum_statistics::general.unique_visitors'), array_reverse($unique_views));
        
        $labels = $all_views->unique('os')->pluck('os');

        $values = [];
        foreach ($labels as $label) {
            array_push($values, $all_views->where('os', $label)->count());
        }

        $oss_chart = Charts::create('donut', 'highcharts')
                                ->title(' ')
                                ->labels($labels)
                                ->values($values)
                                ->colors(['#F44336', '#3F51B5', '#4CAF50', '#FFC107', '#2196F3', '#009688', '#673AB7', '#795548']);

        $labels = $all_views->unique('browser')->pluck('browser');

        $values = [];
        foreach ($labels as $label) {
            array_push($values, $all_views->where('browser', $label)->count());
        }

        $browsers_chart = Charts::create('donut', 'highcharts')
                                ->title(' ')
                                ->labels($labels)
                                ->values($values)
                                ->colors(['#F44336', '#3F51B5', '#4CAF50', '#FFC107', '#2196F3', '#009688', '#673AB7', '#795548']);

        return view('laralum_statistics::index', [
            'latest_views_chart' => $latest_views_chart,
            'browsers_chart' => $browsers_chart,
            'oss_chart' => $oss_chart
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
