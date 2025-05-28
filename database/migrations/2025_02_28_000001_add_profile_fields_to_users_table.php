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
            $table->string('phone')->nullable()->after('email');
            $table->json('tags')->nullable()->after('phone');
            $table->string('aboutme')->nullable()->after('tags');
            $table->string('instagram')->nullable()->after('aboutme');
            $table->string('youtube')->nullable()->after('instagram');
            $table->unsignedBigInteger('certificate_1')->nullable()->after('youtube');
            $table->unsignedBigInteger('certificate_2')->nullable()->after('certificate_1');
            $table->unsignedBigInteger('certificate_3')->nullable()->after('certificate_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'instagram',
                'youtube',
                'certificate_1',
                'certificate_2',
                'certificate_3'
            ]);
        });
    }
};
