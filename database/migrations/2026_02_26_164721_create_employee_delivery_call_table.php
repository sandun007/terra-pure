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
        Schema::create('employee_delivery_call', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('call_number')->unique();
            $table->string('order_code')->nullable();
            $table->date('date')->default(DB::raw('CURRENT_DATE'));
            $table->date('status_date')->nullable();
            $table->string('customer_name');
            $table->string('customer_phone_01', 20);
            $table->string('customer_phone_02', 20)->nullable();
            $table->text('customer_address')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('item_code')->nullable();
            $table->string('item_name');
            $table->integer('qty')->default(1);
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('status', ['Pending', 'packing', 'packed', 'givedriver'])->default('Pending');
            $table->text('remark')->nullable();
            $table->string('platform', 50)->nullable();
            $table->string('ad_name')->nullable();
            $table->string('campaign_name')->nullable();
            $table->string('lead_status', 50)->nullable();
            $table->integer('call_duration')->nullable();
            $table->string('call_recording_url')->nullable();
            $table->boolean('download_status')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index('seller_id');
            $table->index('product_id');
            $table->index('status');
            $table->index('date');
            $table->index('status_date');
            $table->index('customer_phone_01');
            $table->index('item_code');
            $table->index(['seller_id', 'status']);
            $table->index(['seller_id', 'date']);

        });


        Schema::table('employee_delivery_call', function (Blueprint $table) {
            $table->foreign('seller_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('seller_products')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_delivery_call');
    }
};
