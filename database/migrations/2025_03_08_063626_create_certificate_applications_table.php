<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('certificate_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->enum('type',['institute','other']);
            $table->json('cv_path'); // Stores the uploaded CV file path
            $table->enum('status', ['pending', 'approved', 'rejected','reapply'])->default('pending');
            $table->unsignedTinyInteger('reapply_count')->default(0);
            $table->text('admin_comment')->nullable(); // Reason for rejection
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('certificate_applications');
    }
};
