<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class p1_counter_scada extends Model
{
    protected $table = 'counter_scada';      
    protected $connection = 'p1_scada_machine'; 
    public $timestamps = false;             
}
