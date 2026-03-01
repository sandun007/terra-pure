<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Each self-seller's own delivery calls
        Schema::create('self_seller_delivery_calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');       // FK → salers.id
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('call_id')->nullable();

            $table->string('call_number')->unique();
            $table->string('order_code')->nullable();
            $table->date('date')->nullable();
            $table->timestamp('status_date')->nullable();

            $table->string('customer_name');
            $table->string('customer_phone_01');
            $table->string('customer_phone_02')->nullable();
            $table->text('customer_address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();

            $table->unsignedInteger('destination_city_id')->nullable();
            $table->string('destination_state_name')->nullable();

            $table->unsignedInteger('origin_city_id')->nullable();
            $table->string('origin_city_name')->nullable();
            $table->string('origin_state_name')->nullable();

            $table->string('curfox_waybill')->nullable();
            $table->timestamp('curfox_dispatched_at')->nullable();
            $table->text('curfox_error')->nullable();

            // Which Curfox business was used for dispatch
            $table->string('curfox_business_id')->nullable();
            $table->string('curfox_business_name')->nullable();

            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();
            $table->unsignedInteger('qty')->default(1);
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);

            // Pending → packing → packed → givedriver
            $table->string('status')->default('Pending');

            $table->text('remark')->nullable();
            $table->string('platform')->nullable();
            $table->string('ad_name')->nullable();
            $table->string('campaign_name')->nullable();
            $table->string('lead_status')->nullable();
            $table->string('call_duration')->nullable();
            $table->string('call_recording_url')->nullable();
            $table->tinyInteger('download_status')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['seller_id', 'status']);
            $table->index('curfox_waybill');
        });

        // Each self-seller can create their own Curfox businesses
        Schema::create('seller_curfox_businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seller_id');

            // From Curfox API response
            $table->string('curfox_id')->nullable();          // data.id (row id)
            $table->string('ref_no')->nullable();             // MB-0018
            $table->string('business_name');
            $table->string('merchant_id')->nullable();        // data.merchant_id — USE THIS for dispatch

            $table->boolean('is_default')->default(false);

            // Address
            $table->string('address_line_1')->nullable();
            $table->string('address_line_2')->nullable();
            $table->string('address_line_3')->nullable();
            $table->unsignedInteger('city_id')->nullable();   // Curfox city_id
            $table->string('city_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('postal_code')->nullable();

            // Bank details
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_branch')->nullable();

            $table->json('raw_response')->nullable();

            $table->timestamps();

            $table->index('seller_id');
            $table->index(['seller_id', 'is_default']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_curfox_businesses');
        Schema::dropIfExists('self_seller_delivery_calls');
    }
};
