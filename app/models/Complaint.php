<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Complaint extends Eloquent{

    protected $fillable = ['name', 'department_id', 'employee_id', 'customer_id', 'item_id', 'shown_receipt', 'purchase_date', 'description', 'internal_note', 'status', 'customer_notified', 'delivered'];

    public function employees() {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function items(){
        return $this->hasOne(Item::class, 'id', 'item_id');
    }

    public function customers(){
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function departments(){
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function brands(){
        return $this->hasOneThrough(Brand::class, Item::class, 'id', 'id', 'item_id', 'brand_id');
    }

    public function images(){
        return $this->hasMany(Image::class);
    }
}