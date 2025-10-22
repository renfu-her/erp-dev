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
        Schema::create('employee_salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('starts_on');
            $table->decimal('base_salary', 12, 2);
            $table->decimal('allowance', 12, 2)->default(0);
            $table->boolean('locked')->default(false);
            $table->string('note')->nullable();
            $table->timestamps();
            $table->index(['employee_id', 'starts_on']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_salaries');
    }
};
