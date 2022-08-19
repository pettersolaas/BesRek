<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Employee extends Eloquent{

    protected $fillable = ['name'];

    public $timestamps = false;

    public function departments() {
        return $this->belongsToMany(Department::class);
    }

    public function complaints(){
        return $this->hasMany(Complaint::class);
    }
}