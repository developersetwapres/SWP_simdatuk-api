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
        Schema::create('positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->tinyInteger('available')->default(0);
            $table->tinyInteger('filled')->default(0);
            $table->tinyInteger('children')->default(0);
            $table->tinyInteger('type')->comment('1=struktural, 2=fungsional');
            $table->tinyInteger('entity')->comment('1=personil, 2=badan');
            $table->tinyInteger('vertical_order')->default(0);
            $table->tinyInteger('horizontal_order')->default(0);
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
