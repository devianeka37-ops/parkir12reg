<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('tb_kendaraan', function (Blueprint $table) {
        $table->increments('id_kendaraan');
        $table->string('plat_nomor')->unique();
        $table->string('jenis_kendaraan');
        $table->string('nama_pemilik');
    });
    Schema::create('tb_area_parkir', function (Blueprint $table) {
        $table->increments('id_area');
        $table->string('nama_area');
        $table->unsignedInteger('kapasitas');
        $table->unsignedInteger('terisi')->default(0);
    });
    Schema::create('tb_tarif', function (Blueprint $table) {
        $table->increments('id_tarif');
        $table->string('jenis_kendaraan');
        $table->unsignedInteger('tarif_per_jam');
    });
    Schema::create('tb_transaksi', function (Blueprint $table) {
        $table->increments('id_parkir');
        $table->unsignedInteger('id_kendaraan');
        $table->dateTime('waktu_masuk');
        $table->unsignedInteger('id_tarif');
        $table->unsignedInteger('durasi_jam');
        $table->unsignedInteger('biaya_total');
        $table->string('status');
        $table->unsignedInteger('id_area');
    });
    Schema::create('tb_log_aktivitas', function (Blueprint $table) {
        $table->increments('id_log');
        $table->unsignedInteger('id_user')->nullable();
        $table->string('aktivitas');
        $table->dateTime('waktu_aktivitas');
    });
});

afterEach(function () {
    Schema::dropIfExists('tb_log_aktivitas');
    Schema::dropIfExists('tb_transaksi');
    Schema::dropIfExists('tb_tarif');
    Schema::dropIfExists('tb_area_parkir');
    Schema::dropIfExists('tb_kendaraan');
});

test('registered vehicle can be recorded as entering the parking area', function () {
    $vehicleId = DB::table('tb_kendaraan')->insertGetId([
        'plat_nomor' => 'B 1234 XYZ',
        'jenis_kendaraan' => 'motor',
        'nama_pemilik' => 'Pemilik',
    ]);
    $areaId = DB::table('tb_area_parkir')->insertGetId([
        'nama_area' => 'Area A',
        'kapasitas' => 10,
    ]);
    $tariffId = DB::table('tb_tarif')->insertGetId([
        'jenis_kendaraan' => 'motor',
        'tarif_per_jam' => 3000,
    ]);

    $this->postJson('/dashboard/transactions', [
        'plat_nomor' => 'B 1234 XYZ',
        'id_area' => $areaId,
    ])->assertCreated();

    $this->assertDatabaseHas('tb_transaksi', [
        'id_kendaraan' => $vehicleId,
        'id_tarif' => $tariffId,
        'id_area' => $areaId,
        'status' => 'masuk',
    ]);
    $this->assertDatabaseHas('tb_area_parkir', ['id_area' => $areaId, 'terisi' => 1]);
});

test('unregistered vehicle cannot be recorded as entering', function () {
    $areaId = DB::table('tb_area_parkir')->insertGetId([
        'nama_area' => 'Area A',
        'kapasitas' => 10,
    ]);

    $this->postJson('/dashboard/transactions', [
        'plat_nomor' => 'B 9999 XYZ',
        'id_area' => $areaId,
    ])->assertUnprocessable();

    $this->assertDatabaseMissing('tb_kendaraan', ['plat_nomor' => 'B 9999 XYZ']);
    $this->assertDatabaseCount('tb_transaksi', 0);
});
