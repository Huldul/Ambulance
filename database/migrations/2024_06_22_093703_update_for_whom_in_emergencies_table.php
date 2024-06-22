<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForWhomInEmergenciesTable extends Migration
{
    public function up()
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->boolean('for_whom')->change();
        });
    }

    public function down()
    {
        Schema::table('emergencies', function (Blueprint $table) {
            $table->string('for_whom')->change();
        });
    }
}

