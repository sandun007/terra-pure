<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            if (!Schema::hasColumn('brands', 'wholesaler_id')) {
                $table->foreignId('wholesaler_id')->nullable()->constrained('wholesalers')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            if (Schema::hasColumn('brands', 'wholesaler_id')) {
                $table->dropForeign(['wholesaler_id']);
                $table->dropColumn('wholesaler_id');
            }
        });
    }
};
