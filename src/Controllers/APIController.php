<?php

namespace Laralum\Statistics\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laralum\Statistics\Models\View;

class APIController extends Controller
{
    /**
     * Get the most used OS.
     *
     * @return array
     */
    public function getViews(Request $request)
    {
        return ['data' => View::all()->count()];
    }

    /**
     * Get the most used OS.
     *
     * @return array
     */
    public function getUniqueViews(Request $request)
    {
        return ['data' => View::all()->unique('ip')->count()];
    }

    /**
     * Get the most used OS.
     *
     * @return array
     */
    public function getMostUsedOs(Request $request)
    {
        return ['data' => $this->mostUsed('os')];
    }

    /**
     * Get the most used Browser.
     *
     * @return array
     */
    public function getMostUsedBrowser(Request $request)
    {
        return ['data' => $this->mostUsed('browser')];
    }

    /**
     * Get the most used Language.
     *
     * @return array
     */
    public function getMostUsedLanguage(Request $request)
    {
        $countries = json_decode(file_get_contents(__DIR__.'/../countries.json'), true);
        $most_used_lang = $this->mostUsed('browser_locale');

        foreach ($countries as $country) {
            if ($country['code'] == $most_used_lang) {
                $most_used_lang = explode(' ', str_replace(';', '', $country['name']))[0];
                break;
            }
        }

        return ['data' => $most_used_lang];
    }

    /**
     * Get the most used resource.
     *
     * @param string $resource
     *
     * @return \Illuminate\Http\Response
     */
    private function mostUsed($resource)
    {
        $views = View::all();

        $data_helper = $views->pluck($resource)->unique()->map(function ($item, $key) use ($views, $resource) {
            return [$resource => $item, 'number' => $views->where($resource, $item)->count()];
        });

        return $data_helper->where('number', $data_helper->max('number'))->first()[$resource];
    }
}
