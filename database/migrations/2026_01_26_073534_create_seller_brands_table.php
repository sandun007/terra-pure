<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Check if table exists before creating
        if (!Schema::hasTable('seller_brands')) {
            Schema::create('seller_brands', function (Blueprint $table) {
                $table->id();
                $table->foreignId('seller_id')->constrained('sellers')->onDelete('cascade');
                $table->string('code')->unique();
                $table->string('name');
                $table->string('image')->nullable();
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->timestamps();

                $table->index(['seller_id', 'status']);
                $table->unique(['seller_id', 'name']);
            });
        } else {
            // If table exists, check and add missing columns
            if (!Schema::hasColumn('seller_brands', 'seller_id')) {
                Schema::table('seller_brands', function (Blueprint $table) {
                    $table->foreignId('seller_id')->after('id')->nullable()->constrained('sellers')->onDelete('cascade');
                });
            }
        }
    }

    public function down()
    {
        Schema::dropIfExists('seller_brands');
    }
};
