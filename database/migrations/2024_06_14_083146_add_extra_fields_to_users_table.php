<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('iin')->unique();
            $table->string('phone_number')->unique();
            $table->string('full_name');
            $table->date('date_of_birth');
            $table->string('residence');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['iin', 'phone_number', 'full_name', 'date_of_birth', 'residence']);
        });
    }

};
