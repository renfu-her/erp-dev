<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('salary_grade', 50)->nullable()->after('national_id');
            $table->string('labor_grade', 50)->nullable()->after('salary_grade');
            $table->boolean('is_indigenous')->default(false)->after('labor_grade');
            $table->boolean('is_disabled')->default(false)->after('is_indigenous');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['salary_grade', 'labor_grade', 'is_indigenous', 'is_disabled']);
        });
    }
};
