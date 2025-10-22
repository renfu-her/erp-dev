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
        Schema::table('insurance_brackets', function (Blueprint $table) {
            $table->unsignedInteger('occupational_employee')->nullable()->after('health_employer');
            $table->unsignedInteger('occupational_employer')->nullable()->after('occupational_employee');
            $table->unsignedInteger('labor_pension_6_percent')->nullable()->after('occupational_employer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('insurance_brackets', function (Blueprint $table) {
            $table->dropColumn(['occupational_employee', 'occupational_employer', 'labor_pension_6_percent']);
        });
    }
};
