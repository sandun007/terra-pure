<?php
// database/migrations/2024_01_01_000002_add_role_to_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Check if columns exist before adding
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->unsignedBigInteger('role_id')->default(3);
            }

            if (!Schema::hasColumn('users', 'is_wholesaler')) {
                $table->boolean('is_wholesaler')->default(false);
            }

            if (!Schema::hasColumn('users', 'company_name')) {
                $table->string('company_name')->nullable();
            }

            if (!Schema::hasColumn('users', 'tax_number')) {
                $table->string('tax_number')->nullable();
            }
        });

        // Add foreign key constraint only if role_id was added
        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                // Check if foreign key doesn't exist
                $connection = Schema::getConnection();
                $foreignKeys = $connection->getDoctrineSchemaManager()->listTableForeignKeys('users');
                $foreignKeyExists = collect($foreignKeys)->contains(function ($key) {
                    return in_array('role_id', $key->getColumns());
                });

                if (!$foreignKeyExists) {
                    $table->foreign('role_id')
                        ->references('id')
                        ->on('user_roles')
                        ->onDelete('restrict');
                }
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['role_id']);

            // Then drop columns
            $table->dropColumn(['role_id', 'is_wholesaler', 'company_name', 'tax_number']);
        });
    }
}
