<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('prouduk', function (Blueprint $table) {
        $table->id();
        $table->string('nama_produk', 150);
        $table->bigInteger('kategori_id');
        $table->integer('stok')->default(0);
        $table->string('satuan', 20);
        $table->decimal('harga_beli', 12, 2)->nullable();
        $table->decimal('harga_jual', 12, 2)->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prouduk');
    }
};
