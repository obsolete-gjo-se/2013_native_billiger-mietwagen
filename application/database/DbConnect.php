<?php

namespace database;

class DbConnect {
    
    private $dsn = "mysql:host=localhost;billiger-mietwagen";
    private $user = "root";
    private $password = "root";
    public $connect = NULL;

    public function __construct(){
        
        try {
            $this->dbConnect();
        } catch (\PDOException $e) {
            $this->connect = NULL;
        }
        
    }
    
    private function dbConnect(){
        
        if(Null === $this->connect) {
           
            $this->connect = new \PDO($this->dsn, $this->user, $this->password);
        }
    }
}