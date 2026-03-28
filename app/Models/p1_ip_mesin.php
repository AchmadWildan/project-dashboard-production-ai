<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class p1_ip_mesin extends Model
{
    protected $table = 'ip_mesin';
    protected $connection = 'p1_packing_machine';
    public $timestamps = false;
}
