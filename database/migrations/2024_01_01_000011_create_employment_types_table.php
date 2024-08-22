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
        Schema::create('employment_types', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 160);
            $table->tinyInteger('type')->default(1)->comment('1=ASN, 2=NON-ASN, 3=OUTSOURCE');
            $table->tinyInteger('sequence_number')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_types');
    }
};
