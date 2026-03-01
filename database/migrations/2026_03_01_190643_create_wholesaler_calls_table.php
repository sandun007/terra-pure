<?php
// database/migrations/2026_03_02_100000_create_wholesaler_calls_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wholesaler_calls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wholesaler_id');
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
            $table->enum('status', ['Pending', 'Conform', 'Reject', 'Not Answer', 'WhatsApp', 'Other'])->default('Pending');
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

            $table->index('wholesaler_id');
            $table->index('product_id');
            $table->index('status');
            $table->index('date');
            $table->index('status_date');
            $table->index('customer_phone_01');
            $table->index('item_code');
            $table->index(['wholesaler_id', 'status']);
            $table->index(['wholesaler_id', 'date']);
        });

        Schema::table('wholesaler_calls', function (Blueprint $table) {
            $table->foreign('wholesaler_id')->references('id')->on('wholesalers')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('wholesaler_products')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wholesaler_calls');
    }
};
