<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('brands', function (Blueprint $table) {
            // Drop img column if it exists
            if (Schema::hasColumn('brands', 'img')) {
                $table->dropColumn('img');
            }

            // Add image column if it doesn't exist
            if (!Schema::hasColumn('brands', 'image')) {
                $table->string('image')->nullable()->after('status');
            }
        });
    }
};
