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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->string('email', 160)->nullable()->unique();
            $table->string('username', 160)->nullable()->unique();
            $table->string('password', 255)->nullable();
            $table->string('title_prefix', 160)->nullable();
            $table->string('name', 160);
            $table->string('title_suffix', 160)->nullable();
            $table->string('photo_profile', 160)->nullable();
            $table->string('employee_id_number', 20)->nullable()->unique();
            $table->string('employee_registration_number', 20)->nullable()->unique();
            $table->string('place_of_birth', 30)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->tinyInteger('religion')->nullable()->comment('1=Islam, 2=Kristen, 3=Katolik, 4=Hindu, 5=Buddha, 6=Konghucu');
            $table->boolean('gender')->nullable()->comment('true=Pria, false=Wanita');
            $table->tinyInteger('marital_status')->nullable()->comment('1=Belum Menikah, 2=Menikah, 3=Cerai Hidup, 4=Cerai Mati');
            $table->date('marriage_date')->nullable();
            $table->text('marriage_description')->nullable();
            $table->text('marriage_other_notes')->nullable();
            $table->unsignedBigInteger('employment_type_id')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable();
            $table->date('grade_effective_date')->nullable();
            $table->unsignedBigInteger('position_id')->nullable();
            $table->date('position_effective_date')->nullable();
            $table->unsignedBigInteger('echelon_id')->nullable();
            $table->date('echelon_effective_date')->nullable();
            $table->unsignedBigInteger('institution_id')->nullable();
            $table->tinyInteger('education_level')->nullable()->comment('1=SD/Sederajat, 2=SLTP/Sederajat, 3=SLTA/Sederajat, 4=Diploma I/II, 5=Akademik/D3/S.Muda, 6=Diploma IV/Strata I, 7=Strata II, 8=Strata III');
            $table->string('education_name', 160)->nullable();
            $table->year('education_year')->nullable();
            $table->string('employee_id_card_number', 20)->nullable();
            $table->string('employee_id_card', 160)->nullable();
            $table->string('karisu_number', 20)->nullable();
            $table->string('id_tax', 20)->nullable();
            $table->tinyInteger('employment_status')->nullable()->comment('1=Aktif, 2=Pensiun, 3=Berhenti, 4=Meninggal, 5=Alih Status, 6=Aktif Perbantuan Setneg, 7=CLTN, 8=TBLN, 9=Non Aktif');
            $table->date('quit_date')->nullable();
            $table->string('id_number')->nullable();
            $table->string('family_registration_number')->nullable();
            $table->unsignedBigInteger('residence_id')->nullable();
            $table->string('residence_description')->nullable();
            $table->text('current_address')->nullable();
            $table->string('home_phone_number', 20)->nullable();
            $table->string('mobile_phone', 20)->nullable();
            $table->text('office_address', 160)->nullable();
            $table->string('office_phone_number', 20)->nullable();
            $table->string('emergency_contact', 300)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('type')->nullable()->comment('1=ASN, 2=NON ASN, 3=OUTSOURCING');
            $table->boolean('status')->default(false)->comment('true=active, false=deactivate');
            $table->string('verification_code', 160)->nullable()->unique();
            $table->date('expire_at')->nullable();
            $table->date('cpns_effective_date')->nullable();
            $table->integer('years_of_service_total')->nullable();
            $table->integer('month_of_service_total')->nullable();
            $table->date('pns_effective_date')->nullable();
            $table->integer('years_of_service_rank')->nullable();
            $table->integer('month_of_service_rank')->nullable();
            $table->string('office_email')->nullable()->comment('email dinas');
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('employment_type_id')->references('id')->on('employment_types')->onDelete('set null');
            $table->foreign('residence_id')->references('id')->on('residences')->onDelete('set null');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('set null');
            $table->foreign('echelon_id')->references('id')->on('echelons')->onDelete('set null');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
