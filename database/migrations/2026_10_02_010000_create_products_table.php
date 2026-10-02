<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // BIGINT, Auto Increment, Primary Key
            $table->string('code', 50)->unique(); // Kode produk (SKU)
            $table->string('name', 255); // Nama produk
            $table->text('description')->nullable(); // Deskripsi produk
            $table->string('category', 100); // Kategori produk
            $table->decimal('price', 12, 2); // Harga jual (DECIMAL 12,2)
            $table->decimal('cost', 12, 2)->nullable(); // Harga pokok/cost (DECIMAL 12,2)
            $table->integer('stock')->default(0); // Jumlah stok
            $table->integer('min_stock')->default(5); // Stok minimum untuk alert
            $table->string('image_url', 255)->nullable(); // URL foto produk
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};