<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Department extends Eloquent{

    protected $fillable = ['name', 'password', 'display_name'];

    public $timestamps = false;

    public function employees() {
        return $this->belongsToMany(Employee::class);
    }

    public function complaints(){
        return $this->hasMany(Complaint::class);
    }
}