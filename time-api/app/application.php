<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class application extends Model
{
    protected $table = 'application';
    protected $fillable = ['name','image'];
    
 
    public  function applicationUsages()
    {
        return $this->hasMany("App\usage", "application_id");
    }

    public  function applicationRestriction()
    {
        return $this->hasMany("App\restriction", "user_id");

    }

}
