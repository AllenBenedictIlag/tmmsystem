<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("UPDATE tasks SET status = REPLACE(REPLACE(LOWER(TRIM(status)),' ','_'),'-','_') WHERE status IS NOT NULL");
    }

    public function down(): void
    {
        // Irreversible by design.
    }
};