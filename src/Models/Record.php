<?php

namespace Laralum\Statistics\Models;

use Unicodeveloper\Identify\Facades\IdentityFacade as Identify;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;


class Record extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laralum_statistics_records';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type',
        'views', 'sessions'
    ];


    public static function check($name, $type)
    {
        $collection = self::where(['name' => $name, 'type' => $type]);
        if ($collection->count()) {
            $last = $collection->orderBy('id', 'desc')->first();
            if ($last->created_at->lt(Carbon::now()->subDay())) {
                self::create([
                    'name' => $name,
                    'type' => $type,
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
                'name' => $name,
                'type' => $type,
                'views' => 1,
                'sessions' => 1,
            ]);
        }
    }

    /**
     * Add a view row to the database.
     */
    public static function addRecord()
    {
        self::check(Identify::browser()->getName(), 'browser');
        self::check(Identify::os()->getName(), 'os');
        self::check(Identify::lang()->getLanguage(), 'language');
        self::check(Request::url(), 'url');
    }
}
