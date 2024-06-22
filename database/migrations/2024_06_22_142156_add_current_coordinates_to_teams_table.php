<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentCoordinatesToTeamsTable extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('current_coordinates')->nullable();
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn('current_coordinates');
        });
    }
}
