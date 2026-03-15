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
    Schema::create('tasks', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('description')->nullable();

        $table->foreignId('project_id')->constrained()->cascadeOnDelete();
        $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

        $table->timestamp('due_at')->nullable();
        $table->timestamp('scheduled_at')->nullable();

        $table->timestamp('submitted_at')->nullable();
        $table->timestamp('approved_at')->nullable();
        $table->timestamp('completed_at')->nullable();

        $table->string('platform')->nullable();
        $table->foreignId('posted_by')->nullable()->constrained('users')->nullOnDelete();

        $table->string('status')->default('assigned');
        $table->text('rejection_reason')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
