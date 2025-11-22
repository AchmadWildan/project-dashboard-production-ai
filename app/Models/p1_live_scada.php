<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class p1_live_scada extends Model
{
    protected $table = 'live_scada';      
    protected $connection = 'p1_scada_machine'; 
    public $timestamps = false;             
}
