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
            $table->string('name', 160);
            $table->string('photo_profile', 160)->nullable();
            $table->string('id_number')->nullable()->unique();
            $table->string('employee_id_number', 20)->nullable()->unique();
            $table->string('employee_registration_number', 20)->nullable()->unique();
            $table->string('place_of_birth', 30)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->tinyInteger('religion')->default(1)->comment('1=Islam, 2=Kristen, 3=Katolik, 4=Hindu, 5=Buddha, 6=Konghucu');
            $table->boolean('gender')->default(true)->comment('true=Pria, false=Wanita');
            $table->tinyInteger('marital_status')->default(1)->comment('1=Belum Menikah, 2=Menikah, 3=Cerai, 4=Janda, 5=Duda');
            $table->unsignedBigInteger('employment_type_id')->nullable();
            $table->unsignedBigInteger('grade_id')->nullable(); // Dropdown
            $table->date('grade_effective_date')->nullable();
            $table->unsignedBigInteger('position_id')->nullable(); // Dropdown
            $table->unsignedBigInteger('echelon_id')->nullable(); // Dropdown
            $table->date('echelon_effective_date')->nullable();
            $table->unsignedBigInteger('institution_id')->nullable(); // Dropdown
            $table->unsignedBigInteger('organization_id')->nullable(); // Dropdown
            $table->unsignedBigInteger('work_unit_id')->nullable(); // Dropdown
            $table->string('employee_id_card_number', 20)->nullable();
            $table->string('employee_id_card', 160)->nullable();
            $table->string('wife_id_card_number', 20)->nullable();
            $table->string('husband_id_card_number', 20)->nullable();
            $table->string('id_tax', 20)->nullable();
            $table->boolean('employment_status')->default(true)->comment('true=aktif, false=tidak aktif');
            $table->boolean('inner_housing_complex')->default(true)->comment('true=dalam, false=luar');
            $table->text('current_address')->nullable();
            $table->string('home_phone_number', 20)->nullable();
            $table->string('mobile_phone', 20)->nullable();
            $table->text('office_address', 160)->nullable();
            $table->string('office_phone_number', 20)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('type')->nullable()->default(1)->comment('1=ASN, 2=NON ASN, 3=OUTSOURCING');
            $table->boolean('status')->default(false)->comment('true=active, false=deactivate');
            $table->string('verification_code', 160)->nullable()->unique();
            $table->date('expire_at')->nullable();
            $table->timestamp('created_at');
            $table->timestamp('updated_at')->nullable();

            $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('employment_type_id')->references('id')->on('employment_types')->onDelete('set null');
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
