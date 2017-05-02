<?php

namespace Laralum\Statistics\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Unicodeveloper\Identify\Facades\IdentityFacade as Identify;

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
        'user_id', 'ip',
        'browser', 'browser_version',
        'os', 'os_version',
        'locale', 'browser_locale',
        'previous_url', 'next_url',
    ];

    /**
     * Add a view row to the database.
     */
    public static function addView()
    {
        self::create([
            'user_id'         => Auth::check() ? Auth::id() : null,
            'ip'              => self::getIP(),
            'browser'         => Identify::browser()->getName(),
            'browser_version' => Identify::browser()->getVersion(),
            'os'              => Identify::os()->getName(),
            'os_version'      => Identify::os()->getVersion(),
            'locale'          => App::getLocale(),
            'browser_locale'  => Identify::lang()->getLanguage(),
            'next_url'        => Request::url(),

        ]);
    }

   /**
    * Gets the real client IP.
    */
   public static function getIP()
   {
       if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {   //check ip from cloudflare
         $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
       } elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
         $ip = $_SERVER['HTTP_CLIENT_IP'];
       } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
         $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
       } else {
           $ip = $_SERVER['REMOTE_ADDR'];
       }

       return $ip;
   }
}
