<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddShowTillPosSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
       Schema::table('pos_settings', function (Blueprint $table) {
        $table->boolean('show_till')->default(false)->after('show_customer');
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pos_settings', function (Blueprint $table) {
            $table->dropColumn('show_till');
        });
    }
}
