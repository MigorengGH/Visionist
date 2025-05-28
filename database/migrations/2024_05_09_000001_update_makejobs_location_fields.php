<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // First ensure is_online exists and has correct default
        if (!Schema::hasColumn('makejobs', 'is_online')) {
            Schema::table('makejobs', function (Blueprint $table) {
                $table->boolean('is_online')->default(false)->after('status');
            });
        }

        // Update existing records
        DB::table('makejobs')->update([
            'is_online' => false,
            'location_type' => 'physical'
        ]);

        // Make state_id and district_id nullable for online jobs
        Schema::table('makejobs', function (Blueprint $table) {
            $table->unsignedBigInteger('state_id')->nullable()->change();
            $table->unsignedBigInteger('district_id')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('makejobs', function (Blueprint $table) {
            $table->unsignedBigInteger('state_id')->nullable(false)->change();
            $table->unsignedBigInteger('district_id')->nullable(false)->change();
        });
    }
};
