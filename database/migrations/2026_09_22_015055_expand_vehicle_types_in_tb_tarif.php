<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE tb_tarif MODIFY jenis_kendaraan ENUM('motor','mobil','becak','truk','bus','sepeda','lainnya') DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE tb_tarif MODIFY jenis_kendaraan ENUM('motor','mobil','lainnya') DEFAULT NULL");
    }
};
