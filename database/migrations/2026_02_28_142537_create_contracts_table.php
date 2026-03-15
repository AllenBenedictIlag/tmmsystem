<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();

            // CORE uploads draft contract PDF
            $table->string('draft_pdf_path');

            // CEO uploads signed PDF (or CORE stores the signed file)
            $table->string('signed_pdf_path')->nullable();
            $table->timestamp('signed_at')->nullable();

            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete(); // CORE
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('contracts');
    }
};
