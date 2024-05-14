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
        Schema::create('disciplinary_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 160);
            $table->string('description');
            $table->float('performance_allowance_deduction')->nullable()->comment('persentase pemontongan tunjangan kinerja');
            $table->unsignedInteger('performance_allowance_duration')->nullable()->comment('jangka waktu pemotongan (dalam bulan)');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('disciplinary_types');
    }
};
