<?php
checkLogin();

// Controller for home page
class Brands extends Controller {


    Public function __construct() {
       $this->brands = $this->model('Brand');
     }

    
    // Get JSON list of brands
    public function getBrands() {
        if(!empty($_GET['search'])) {
            $search = $_GET['search'] . "%";

            $brands = $this->brands->where('name', 'LIKE', $search)->get();

            $response = array();
            foreach ($brands as $brand) {
                $response[] = array(
                    "label" => $brand->name,
                    "id" => $brand->id
                );
           }
        }
        echo json_encode($response);
        exit;
    }
}