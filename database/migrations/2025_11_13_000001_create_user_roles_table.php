<?php
// database/migrations/2024_01_01_000001_create_user_roles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration
{
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->id(); // This creates an auto-incrementing BIGINT UNSIGNED column
            $table->string('name')->unique(); // e.g., 'admin', 'wholesaler', 'retail_seller'
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default roles
        DB::table('user_roles')->insert([
            ['name' => 'admin', 'description' => 'Administrator'],
            ['name' => 'wholesaler', 'description' => 'Wholesale Seller'],
            ['name' => 'retail_seller', 'description' => 'Retail Seller'],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
