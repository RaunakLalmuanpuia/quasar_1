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
        Schema::create('apply_roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('email');
            $table->string('company_id');
            $table->string('address');
            $table->string('role_applied');
            $table->enum('role_status', ['accepted', 'rejected', 'pending'])->default('pending')->nullable();
            $table->string('param1')->nullable();
            $table->string('param2')->nullable();
            $table->string('param3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apply_roles');
    }
};
