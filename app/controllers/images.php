<?php
checkLogin();

class Images extends Controller {

    Public function __construct() {
        $this->images = $this->model('Image');
        $this->departments = $this->model('Department');        
        $this->complaints = $this->model('Complaint');        
     }




        // Checks if a complaint exists
        public function complaintExists($complaint_id){
            if($this->complaints->where('id', '=', $complaint_id)->exists()){
                return true;
            } else {
                return false;
            }
        }
}