<?php

namespace Laralum\Statistics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class View extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laralum_statistics_views';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'views', 'sessions',
    ];

    /**
     * Add a view row to the database.
     */
    public static function addView()
    {
        $allViews = self::all();
        if ($allViews->count()) {
            $last = $allViews->last();
            if ($last->created_at->lt(Carbon::now()->subMinutes(60))) {
                self::create([
                    'views' => 1,
                    'sessions' => !Session::has('laralum_statistics_record'),
                ]);
            } else {
                $last->update([
                    'views' => $last->views + 1,
                    'sessions' => $last->sessions + !Session::has('laralum_statistics_record'),
                ]);
            }
        } else {
            self::create([
                'views' => 1,
                'sessions' => 1,
            ]);
        }
    }
}
