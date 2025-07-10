<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\text;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('pengeluaran_id');
            $table->bigInteger('produk_id');
            $table->decimal('harga_satuan', 12, 2)->nullable(); 
            $table->integer('qty');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pengeluarans');
    }
};
