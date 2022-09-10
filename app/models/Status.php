<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Status extends Eloquent{

    public $timestamps = false;

    public function complaints() {
        return $this->belongsTo(Complaint::class, 'id', 'status_id');
    }
}