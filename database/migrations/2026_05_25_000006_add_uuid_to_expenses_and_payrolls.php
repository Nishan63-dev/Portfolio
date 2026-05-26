<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        DB::table('expenses')->whereNull('uuid')->orderBy('id')->chunk(100, function ($expenses) {
            foreach ($expenses as $expense) {
                DB::table('expenses')
                    ->where('id', $expense->id)
                    ->update(['uuid' => (string) Str::uuid()]);
            }
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        DB::table('payrolls')->whereNull('uuid')->orderBy('id')->chunk(100, function ($payrolls) {
            foreach ($payrolls as $payroll) {
                DB::table('payrolls')
                    ->where('id', $payroll->id)
                    ->update(['uuid' => (string) Str::uuid()]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });

        Schema::table('payrolls', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
