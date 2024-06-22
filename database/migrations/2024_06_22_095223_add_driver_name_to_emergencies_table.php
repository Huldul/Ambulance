<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDriverNameToEmergenciesTable extends Migration
{
    public function up()
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->string('driver_name')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->dropColumn('driver_name');
        });
    }
}
