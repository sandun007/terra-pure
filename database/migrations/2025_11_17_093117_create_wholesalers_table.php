<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wholesalers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('address');
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('district');
            $table->string('phone');
            $table->string('whatsapp')->nullable();
            $table->string('email')->unique();
            $table->string('NIC_no')->unique()->nullable();
            $table->string('NIC_front')->nullable();
            $table->string('NIC_back')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('business_name')->nullable();
            $table->string('img')->nullable();
            $table->string('view_password')->nullable();
            $table->string('type')->default('wholesaler');
            $table->integer('stock')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wholesalers');
    }
};
