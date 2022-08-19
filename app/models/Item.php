<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Item extends Eloquent{

    protected $fillable = ['brand_id', 'model', 'size', 'color'];

    public $timestamps = false;

    public function complaints() {
        return $this->belongsTo(Complaint::class, 'id', 'item_id');
    }

    public function brands(){
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }
    
    // public function brands(){
    //     return $this->hasOne(Brand::class, 'id');
    // }
}