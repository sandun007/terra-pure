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
        Schema::create('salers', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('address');
            $table->string('district');
            $table->string('phone');
            $table->string('whatsapp');
            $table->string('email');
            $table->string('NIC_no');
            $table->string('NIC_front');
            $table->string('NIC_back');
            $table->string('username');
            $table->string('password');
            $table->string('view_password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salers');
    }
};
