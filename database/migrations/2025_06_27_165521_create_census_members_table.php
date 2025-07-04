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
        Schema::create('census_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('census_form_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->date('dob')->nullable();
            $table->enum('marital_status', ['Unmarried', 'Married', 'Widowed', 'Divorced'])->nullable();
            $table->string('education')->nullable();
            $table->string('occupation')->nullable();
            $table->string('income_source')->nullable();
            $table->string('mobile')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('identity_proof')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('census_members');
    }
};
