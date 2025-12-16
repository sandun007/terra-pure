<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmployeeCodeAndFileNoToInvParasTable extends Migration
{
    public function up()
    {
        Schema::table('inv_paras', function (Blueprint $table) {
            if (!Schema::hasColumn('inv_paras', 'employee_code')) {
                $table->integer('employee_code')->default(1);
            }
            if (!Schema::hasColumn('inv_paras', 'file_no')) {
                $table->integer('file_no')->default(1);
            }
        });
    }

    public function down()
    {
        Schema::table('inv_paras', function (Blueprint $table) {
            $table->dropColumn(['employee_code', 'file_no']);
        });
    }
}
