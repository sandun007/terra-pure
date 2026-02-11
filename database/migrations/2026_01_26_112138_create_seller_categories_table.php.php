<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Force drop the table if it exists
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::statement('DROP TABLE IF EXISTS seller_categories');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        Schema::create('seller_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id')->nullable();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('add_by')->default('seller');
            $table->string('update_by')->default('seller');
            $table->tinyInteger('cancel')->default(0);
            $table->tinyInteger('seller_code')->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['seller_id', 'status']);
            $table->index(['seller_id', 'code']);
            $table->index(['seller_id', 'seller_code']);
        });

        // Add foreign key only if sellers table exists
        if (Schema::hasTable('sellers')) {
            Schema::table('seller_categories', function (Blueprint $table) {
                $table->foreign('seller_id')
                    ->references('id')
                    ->on('sellers')
                    ->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('seller_categories');
    }
};
