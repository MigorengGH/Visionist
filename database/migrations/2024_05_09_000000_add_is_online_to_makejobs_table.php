<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('makejobs', function (Blueprint $table) {
            $table->boolean('is_online')->default(false)->after('status');
            $table->string('location_type')->default('physical')->after('is_online');
        });
    }

    public function down()
    {
        Schema::table('makejobs', function (Blueprint $table) {
            $table->dropColumn(['is_online', 'location_type']);
        });
    }
};
