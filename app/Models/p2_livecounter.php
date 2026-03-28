<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class p2_livecounter extends Model
{
    protected $table = 'live_counter';      // nama tabel di database
    protected $connection = 'p2_packing_machine'; // koneksi database khusus
    public $timestamps = false;             // matikan jika tidak ada kolom created_at/updated_at
}
