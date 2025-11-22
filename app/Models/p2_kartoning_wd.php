<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class p2_kartoning_wd extends Model
{
    protected $table = 'kartoning_wd1';      // nama tabel di database
    protected $connection = 'p2_output_fg'; // koneksi database khusus
    public $timestamps = false;             // matikan jika tidak ada kolom created_at/updated_at
}
