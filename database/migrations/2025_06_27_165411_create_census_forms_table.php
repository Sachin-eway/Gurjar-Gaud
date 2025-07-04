<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('census_forms', function (Blueprint $table) {
            $table->id();
            $table->string('family_uid')->unique();
            $table->string('head_name');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('dob')->nullable();
            $table->string('father_or_husband_name')->nullable();
            $table->string('caste')->nullable();
            $table->string('family_deity')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->string('identity_proof')->nullable(); // voter/aadhaar/other
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            $table->integer('total_members')->default(0);
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('census_forms');
    }
};
