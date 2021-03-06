<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function getUsersEmail()
    {
        $emails = [];
        foreach ($this->users as $user){
            array_push($emails, $user->email);
        }
        return $emails;
    }

    public function files(){
        return $this->hasMany('App\File');
    }
}
