<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Image extends Eloquent{

    protected $fillable = ['complaint_id', 'filename', 'thumbnail'];

    public $timestamps = false;

    public function complaints() {
        return $this->belongsTo(Complaint::class, 'complaint_id', 'id');
    }
}