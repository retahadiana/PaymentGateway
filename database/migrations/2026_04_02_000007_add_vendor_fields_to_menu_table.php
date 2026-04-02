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
        Schema::table('menu', function (Blueprint $table) {
            if (! Schema::hasColumn('menu', 'kategori')) {
                $table->string('kategori')->nullable()->after('harga');
            }

            if (! Schema::hasColumn('menu', 'detail')) {
                $table->text('detail')->nullable()->after('kategori');
            }

            if (! Schema::hasColumn('menu', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('detail');
            }

            if (! Schema::hasColumn('menu', 'stok')) {
                $table->integer('stok')->default(0)->after('deskripsi');
            }

            if (! Schema::hasColumn('menu', 'aktif')) {
                $table->boolean('aktif')->default(true)->after('stok');
            }

            if (! Schema::hasColumn('menu', 'gambar')) {
                $table->string('gambar')->nullable()->after('path_gambar');
            }

            if (! Schema::hasColumn('menu', 'created_at')) {
                $table->timestamp('created_at')->nullable()->after('aktif');
            }

            if (! Schema::hasColumn('menu', 'updated_at')) {
                $table->timestamp('updated_at')->nullable()->after('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            $columns = [];

            foreach (['kategori', 'detail', 'deskripsi', 'stok', 'aktif', 'gambar', 'created_at', 'updated_at'] as $column) {
                if (Schema::hasColumn('menu', $column)) {
                    $columns[] = $column;
                }
            }

            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};