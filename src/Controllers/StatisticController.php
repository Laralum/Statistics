<?php

namespace Laralum\Statistics\Controllers;

use App\Http\Controllers\Controller;
use ConsoleTVs\Charts\Facades\Charts;
use Laralum\Statistics\Models\View;
use Laralum\Statistics\Models\Record;
use Carbon\Carbon;
use Aitor24\Localizer\Facades\LocalizerFacade as Localizer;

class StatisticController extends Controller
{

    public function deleteOldRecords() {
        View::where('created_at', '<', Carbon::now()->subWeek())->each(function ($view) {
            $view->delete();
        });

        Record::where('created_at', '<', Carbon::now()->subWeek())->each(function ($record) {
            $record->delete();
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        self::deleteOldRecords();

        $views = View::all();
        $records = Record::all();

        $labels = [];
        $normal_views = [];
        $sessions = [];
        for ($i = 6; $i >= 0; $i--) {
            $time = Carbon::now()->subDays($i);
            $nv = View::whereDate('created_at', $time->toDateString());
            array_push($labels, $time->toFormattedDateString());
            array_push($normal_views, $nv->pluck('views')->sum());
            array_push($sessions, $nv->pluck('sessions')->sum());
        }

        $latest_views_chart = Charts::multi('bar', 'highcharts')
                                ->title(' ')->elementLabel(__('laralum_statistics::general.views'))
                                ->labels($labels)
                                ->dataset(__('laralum_statistics::general.views'), $normal_views)
                                ->dataset(__('laralum_statistics::general.sessions'), $sessions);

        $oss = $records->where('type', 'os')->unique('name');

        $valuesOSS = [];
        foreach ($oss as $os) {
            array_push($valuesOSS, $oss->where('name', $os->name)->pluck('sessions')->sum());
        }

        $oss_chart = Charts::create('donut', 'highcharts')
                                ->title(' ')
                                ->labels($oss->pluck('name'))
                                ->values($valuesOSS)
                                ->colors(['#F44336', '#3F51B5', '#4CAF50', '#FFC107', '#2196F3', '#009688', '#673AB7', '#795548']);

        $browsers = $records->where('type', 'browser')->unique('name');

        $valuesBRS = [];
        foreach ($browsers as $browser) {
            array_push($valuesBRS, $browsers->where('name', $browser->name)->pluck('sessions')->sum());
        }

        $browsers_chart = Charts::create('donut', 'highcharts')
                                ->title(' ')
                                ->labels($browsers->pluck('name'))
                                ->values($valuesBRS)
                                ->colors(['#F44336', '#3F51B5', '#4CAF50', '#FFC107', '#2196F3', '#009688', '#673AB7', '#795548']);

        $langs = $records->where('type', 'language')->unique('name');
        $valuesLNG = [];
        foreach ($langs as $lang) {
            array_push($valuesLNG, $langs->where('name', $lang->name)->pluck('sessions')->sum());
        }

        return view('laralum_statistics::index', [
            'latest_views_chart' => $latest_views_chart,
            'browsers_chart'     => $browsers_chart,
            'oss_chart'          => $oss_chart,
            'views'              => $views->pluck('views')->sum(),
            'sessions'           => $views->pluck('sessions')->sum(),
            'most_used'          => [
                'language' => Localizer::getLanguage($records
                                ->where('sessions', collect($valuesLNG)->max())
                                ->where('type', 'language')
                                ->first()->name),
                'browser' => $records
                                ->where('sessions', collect($valuesBRS)->max())
                                ->where('type', 'browser')
                                ->first()->name,
                'os' => $records
                                ->where('sessions', collect($valuesOSS)->max())
                                ->where('type', 'os')
                                ->first()->name
            ]
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

        Record::all()->each(function ($record) {
            $record->delete();
        });

        return redirect()->route('laralum::statistics.index')->with('success', __('laralum_statistics::general.statistics_restarted'));
    }
}
