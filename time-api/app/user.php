<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class user extends Model
{
    protected $table = 'users';
    
 

    public  function userUsages()
    {
        return $this->hasMany("App\usage", "user_id");
    }

    public  function userRestrictions()
    {
        return $this->hasMany('App\restriction', "user_id");
    }
}
