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
        Schema::create('training_levels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('level_name', 150);
            $table->tinyInteger('level_type')->nullable()->comment('1=Jenjang Struktural, 2=Jenjang Fungsional');
            $table->string('description', 255)->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_levels');
    }
};
