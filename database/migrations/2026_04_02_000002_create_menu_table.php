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
        if (Schema::hasTable('menu')) {
            return;
        }
        Schema::create('menu', function (Blueprint $table) {
            $table->id('idmenu');
            $table->unsignedBigInteger('id_vendor');
            $table->string('nama_menu');
            $table->decimal('harga', 10, 2);
            $table->text('detail')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->string('kategori')->nullable();
            $table->integer('stok')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
            
            $table->foreign('id_vendor')->references('id_vendor')->on('vendor')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
