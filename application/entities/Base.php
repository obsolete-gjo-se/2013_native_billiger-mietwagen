<?php
namespace entities;

class Base {
    
    protected $dbh = NULL;

    public function __construct(){
        $this->dbh = new \database\DbConnect();
    }
    
    public function __get($property){
        return $this->$property;
    }
    
    public function __set($property, $value){
        $this->$property = $value;
    }
}

?>