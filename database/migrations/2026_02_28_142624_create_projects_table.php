<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_smm_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamp('planning_due_at')->nullable(); // adviser requirement
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            // gate planning with contract (recommended)
            $table->foreignId('contract_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name'); // e.g. "Client X - March Campaign"
            $table->text('description')->nullable();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // CORE
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('projects');
    }
};