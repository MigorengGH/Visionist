<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('makejobs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('set null');
            $table->foreignId('district_id')->nullable()->constrained('districts')->onDelete('set null');
            $table->json('tags')->nullable();
            $table->decimal('budget', 10, 2)->nullable();
            $table->string('status')->default('open'); // open, closed, accepted
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // job creator
            $table->foreignId('accepted_user_id')->nullable()->constrained('users')->cascadeOnDelete(); // accepted freelancer
            $table->decimal('negotiated_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('makejobs');
    }
};
