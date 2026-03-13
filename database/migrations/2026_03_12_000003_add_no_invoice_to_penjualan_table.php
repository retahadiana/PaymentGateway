<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('penjualan')) {
            return;
        }

        if (!Schema::hasColumn('penjualan', 'no_invoice')) {
            Schema::table('penjualan', function (Blueprint $table) {
                $table->string('no_invoice', 30)->nullable()->after('total');
                $table->index('no_invoice');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('penjualan') || !Schema::hasColumn('penjualan', 'no_invoice')) {
            return;
        }

        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropIndex(['no_invoice']);
            $table->dropColumn('no_invoice');
        });
    }
};
