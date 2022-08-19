<?php


trait FunctionTrait {

    public function printError($msg){
        echo $msg;
    }


    public function hello(){
        echo "hello";
    }

}


trait EmployeeTrait {
    public function printEmployee($name) {
        echo "Employee: " . $name;
    }
}



class TesterClass {

    use FunctionTrait;
    use EmployeeTrait;
}

$test = new TesterClass;

$test->hello();