<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class application extends Model
{
    protected $table = 'application';
    protected $fillable = ['name','image'];
    
 
}
