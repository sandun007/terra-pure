<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('salers', function (Blueprint $table) {
            if (!Schema::hasColumn('salers', 'postal_code')) {
                $table->string('postal_code')->nullable()->after('country');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('salers', function (Blueprint $table) {
            if (Schema::hasColumn('salers', 'postal_code')) {
                $table->dropColumn('postal_code');
            }
        });
    }
};
