<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParkirKendaraan extends Model
{
    protected $table = 'tb_kendaraan';

    protected $primaryKey = 'id_kendaraan';

    public $timestamps = false;

    protected $fillable = [
        'plat_nomor',
        'jenis_kendaraan',
        'nama_pemilik',
    ];
}
