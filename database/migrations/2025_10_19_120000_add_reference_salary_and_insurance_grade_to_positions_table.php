<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->decimal('reference_salary', 14, 2)->nullable()->after('grade');
            $table->unsignedInteger('insurance_grade')->nullable()->after('reference_salary');
            $table->json('insurance_snapshot')->nullable()->after('insurance_grade');
        });
    }

    public function down(): void
    {
        Schema::table('positions', function (Blueprint $table) {
            $table->dropColumn(['reference_salary', 'insurance_grade', 'insurance_snapshot']);
        });
    }
};

