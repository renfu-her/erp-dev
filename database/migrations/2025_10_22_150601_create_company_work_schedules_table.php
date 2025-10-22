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
        Schema::create('company_work_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->date('effective_from');
            $table->date('effective_until');
            $table->time('work_start_time');
            $table->time('work_end_time');
            $table->time('lunch_start_time');
            $table->time('lunch_end_time');
            $table->decimal('working_hours', 4, 2)->comment('Calculated working hours excluding lunch');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['company_id', 'effective_from', 'effective_until'], 'company_schedules_effective_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_work_schedules');
    }
};
