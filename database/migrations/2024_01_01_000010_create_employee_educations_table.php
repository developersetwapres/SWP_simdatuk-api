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
        Schema::create('employee_educations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->smallInteger('level')->default(1)->comment('1=TK / Sederajat, 2=SD / Sederajat, 3=SLTP / Sederajat, 4=SLTA / Sederajat, 5=Diploma I / II, 6=Akademi / Diploma III / Sarjana Muda, 7=Diploma IV / Strata I, 8=Strata II, 9=Strata III');
            $table->string('name', 160);
            $table->string('faculty', 160);
            $table->string('major', 160);
            $table->smallInteger('status')->default(1)->comment('1=Lulus, 2=DO, 3=Aktif, 4=Non-Aktif, 5=Mengundurkan diri');
            $table->date('year_of_graduation');
            $table->text('description');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_educations');
    }
};
