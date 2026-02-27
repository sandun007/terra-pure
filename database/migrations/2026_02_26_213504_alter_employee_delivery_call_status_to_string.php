<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Convert ENUM -> VARCHAR (keeps existing values)
        DB::statement("ALTER TABLE employee_delivery_call MODIFY status VARCHAR(50) NOT NULL DEFAULT 'Pending'");
    }

    public function down(): void
    {
        // Optional rollback: convert back to enum (ONLY if you want)
        DB::statement("ALTER TABLE employee_delivery_call MODIFY status ENUM('Pending','packing','packed','givedriver') NOT NULL DEFAULT 'Pending'");
    }
};
