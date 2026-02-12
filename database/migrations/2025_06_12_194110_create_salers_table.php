<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('salers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('view_password')->nullable();

            // Contact Information
            $table->string('phone');
            $table->string('whatsapp')->nullable();

            // Location Information
            $table->text('address');
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->default('Sri Lanka');
            $table->string('postal_code');

            // NIC/Identity Documents
            $table->string('NIC_no')->nullable();
            $table->string('NIC_front')->nullable();
            $table->string('NIC_back')->nullable();

            // Profile Images
            $table->string('img')->nullable(); // Main profile image
            $table->string('profile_image')->nullable(); // Alternative profile image field

            // Business Information
            $table->string('type')->nullable(); // seller type
            $table->enum('status', ['active', 'inactive', 'pending', 'suspended'])->default('pending');

            // Financial Information
            $table->decimal('deposit', 15, 2)->default(0);
            $table->decimal('cal_deposit', 15, 2)->default(0);
            $table->decimal('cost', 15, 2)->default(0);
            $table->decimal('total_sales', 15, 2)->default(0);

            // Statistics
            $table->integer('order_count')->default(0);

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for better performance
            $table->index('code');
            $table->index('status');
            $table->index('city');
            $table->index('district');
        });
    }

    public function down()
    {
        Schema::dropIfExists('salers');
    }
};
