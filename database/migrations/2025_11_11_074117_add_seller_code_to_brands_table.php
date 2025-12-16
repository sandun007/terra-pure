<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            if (!Schema::hasColumn('brands', 'seller_code')) {
                $table->string('seller_code')->default('0')->after('id');
            }
        });
    }

    public function down()
    {
        Schema::table('brands', function (Blueprint $table) {
            if (Schema::hasColumn('brands', 'seller_code')) {
                $table->dropColumn('seller_code');
            }
        });
    }
};
