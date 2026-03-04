<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelfSellerDeliveryCallsTable extends Migration
{
    public function up()
    {
        Schema::create('self_seller_delivery_calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('order_code')->nullable();
            $table->string('call_number')->unique();
            $table->date('date')->nullable();
            $table->datetime('status_date')->nullable();

            // Customer info
            $table->string('customer_name');
            $table->string('customer_phone_01');
            $table->string('customer_phone_02')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->unsignedBigInteger('destination_city_id')->nullable();
            $table->string('destination_state_name')->nullable();

            // Origin (pickup location)
            $table->unsignedBigInteger('origin_city_id')->nullable();
            $table->string('origin_city_name')->nullable();
            $table->string('origin_state_name')->nullable();

            // Product info
            $table->string('item_code');
            $table->string('item_name');
            $table->integer('qty')->default(1);
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);

            // Status and tracking
            $table->string('status')->default('pending');
            $table->text('remark')->nullable();
            $table->string('platform')->nullable();
            $table->string('ad_name')->nullable();
            $table->string('campaign_name')->nullable();
            $table->string('lead_status')->nullable();
            $table->string('call_duration')->nullable();
            $table->text('call_recording_url')->nullable();
            $table->boolean('download_status')->default(false);

            // Curfox integration
            $table->string('curfox_waybill')->nullable();
            $table->timestamp('curfox_dispatched_at')->nullable();
            $table->text('curfox_error')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('seller_id')->references('id')->on('salers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('seller_products')->onDelete('set null');
            $table->index('status');
            $table->index('call_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('self_seller_delivery_calls');
    }
}
