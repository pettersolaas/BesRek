<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Brand extends Eloquent{

    protected $fillable = ['name'];

    public $timestamps = false;

    public function items() {
        return $this->hasMany(Item::class, 'id', 'brand_id');
    }

    // public function itemowner(){
    //     return $this->hasOneThrough(Complaint::class, Item::class, 'brand_id', 'item_id', 'id', 'id');
    // }

    // public function items() {
    //     return $this->hasMany(Item::class);
    // }
}