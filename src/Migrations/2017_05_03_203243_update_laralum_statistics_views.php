<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laralum\Statistics\Models\View;

class UpdateLaralumStatisticsViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Delete all old views records
        View::all()->each(function ($view) {
            $view->delete();
        });

        // Update table views
        Schema::table('laralum_statistics_views', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('ip');
            $table->dropColumn('browser');
            $table->dropColumn('browser_version');
            $table->dropColumn('os');
            $table->dropColumn('os_version');
            $table->dropColumn('locale');
            $table->dropColumn('browser_locale');
            $table->dropColumn('next_url');
            $table->integer('views');
            $table->integer('sessions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
