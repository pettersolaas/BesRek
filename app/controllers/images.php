<?php
checkLogin();

class Images extends Controller {

    Public function __construct() {
        $this->images = $this->model('Image');
        $this->departments = $this->model('Department');        
        $this->complaints = $this->model('Complaint');        
     }

    // 
    public function index() {

        // Retrieve all employees
        $images = $this->complaints->find(1)->images;

        dd($images);
    }
}