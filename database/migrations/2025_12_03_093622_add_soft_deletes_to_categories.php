<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Check if column doesn't exist before adding
            if (!Schema::hasColumn('categories', 'deleted_at')) {
                $table->softDeletes()->after('updated_at');
            }

            // Make sure wholesaler_id exists
            if (!Schema::hasColumn('categories', 'wholesaler_id')) {
                $table->foreignId('wholesaler_id')->nullable()->after('id');
            }

            // Add column for wholesaler image
            if (!Schema::hasColumn('categories', 'image')) {
                $table->string('image')->nullable()->after('img');
            }

            // Update status column if needed
            if (Schema::hasColumn('categories', 'status')) {
                $table->string('status')->default('active')->change();
            }

            // Add indexes
            $table->index(['wholesaler_id', 'deleted_at']);
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['wholesaler_id', 'image']);
            $table->dropIndex(['wholesaler_id', 'deleted_at']);
        });
    }
};
