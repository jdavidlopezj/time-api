<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class usage extends Model
{
    protected $table = 'app_usage';
    protected $fillable = ['day','useTime','location','user_id','application_id'];
    
}
