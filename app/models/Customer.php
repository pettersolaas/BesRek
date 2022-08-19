<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Customer extends Eloquent{

    protected $fillable = ['name', 'phone', 'email'];

    public $timestamps = false;

    public function complaints() {
        return $this->hasMany(Complaint::class);
    }
}