<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class p1_mapping_mesin extends Model
{
    protected $table = 'mapping_mesin';      
    protected $connection = 'p1_scada_machine'; 
    public $timestamps = false;             
}
