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
        if (! Schema::hasTable('pesanan') || Schema::hasColumn('pesanan', 'alamat_pengiriman')) {
            return;
        }

        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('alamat_pengiriman')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('pesanan') || ! Schema::hasColumn('pesanan', 'alamat_pengiriman')) {
            return;
        }

        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('alamat_pengiriman');
        });
    }
};
