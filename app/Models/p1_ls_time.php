<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class p1_ls_time extends Model
{
    protected $table = 'ls_time';      
    protected $connection = 'p1_scada_machine'; 
    public $timestamps = false;             
}
