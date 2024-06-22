<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTeamsTable extends Migration
{
    public function up()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('car')->nullable()->after('id');
            $table->string('driver')->nullable()->after('car');
            $table->string('feldsher')->nullable()->after('driver');
            $table->string('type')->nullable()->after('feldsher');
        });
    }

    public function down()
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['car', 'driver', 'feldsher', 'type']);
        });
    }
}
