<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileNoToInvParasTable extends Migration
{
    public function up()
    {
        Schema::table('inv_paras', function (Blueprint $table) {
            // Add file_no without specifying position
            $table->integer('file_no')->default(1);
        });
    }

    public function down()
    {
        Schema::table('inv_paras', function (Blueprint $table) {
            $table->dropColumn('file_no');
        });
    }
}
