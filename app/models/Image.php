<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Image extends Eloquent{

    protected $fillable = ['complaint_id', 'filename'];

    public $timestamps = false;

    public function complaints() {
        return $this->belongsTo(Complaint::class);
    }
}