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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('nic_no')->unique();
            $table->string('mobile_no');
            $table->string('whatsapp_no');
            $table->string('email')->nullable();
            $table->string('address');
            $table->string('post_code');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('view_password');
            $table->string('district');
            $table->string('nic_front');
            $table->string('nic_back');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
