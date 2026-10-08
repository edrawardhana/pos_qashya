<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // admin, kasir
            $table->string('label');          // Admin, Kasir
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['name' => 'admin', 'label' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'kasir', 'label' => 'Kasir', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};