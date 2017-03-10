<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLaralumStatisticsViews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laralum_statistics_views', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->ipAddress('ip');
            $table->string('browser');
            $table->string('browser_version');
            $table->string('os');
            $table->string('os_version');
            $table->string('locale');
            $table->string('browser_locale');
            $table->string('next_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laralum_statistics_views');
    }
}
