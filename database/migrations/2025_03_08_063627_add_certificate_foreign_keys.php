<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('certificate_1')
                ->references('id')
                ->on('certificate_applications')
                ->onDelete('set null');

            $table->foreign('certificate_2')
                ->references('id')
                ->on('certificate_applications')
                ->onDelete('set null');

            $table->foreign('certificate_3')
                ->references('id')
                ->on('certificate_applications')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['certificate_1']);
            $table->dropForeign(['certificate_2']);
            $table->dropForeign(['certificate_3']);
        });
    }
};
