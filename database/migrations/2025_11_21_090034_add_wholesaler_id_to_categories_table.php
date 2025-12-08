<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            // Add wholesaler support columns
            $table->foreignId('wholesaler_id')->nullable()->constrained('wholesalers')->onDelete('cascade');
            $table->string('code')->unique()->change();
            $table->string('image')->nullable()->after('img');
            $table->enum('status', ['active', 'inactive'])->default('active')->change();
            $table->softDeletes();

            // Add indexes
            $table->index(['wholesaler_id', 'status']);
            $table->index(['wholesaler_id', 'code']);
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['wholesaler_id']);
            $table->dropColumn(['wholesaler_id', 'image']);
            $table->dropSoftDeletes();
            $table->dropIndex(['wholesaler_id', 'status']);
            $table->dropIndex(['wholesaler_id', 'code']);
        });
    }
};
