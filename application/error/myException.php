<?php
namespace error;

class myException extends \Exception {
    
    private $errorMsg = array(
        "0/Unbekannter Fehler aufgetreten", 
        "1/Fehler im SQL Statement<br />", 
        "2/Datenbank kann nicht geöffnet werden", 
        "3/Abfrage gibt kein Ergebnis zurück"
    );

    public function __construct($message, $code = 0){
        
        file_put_contents("error.log", 
                $message . "\nZeile:  " . $this->getLine() . "\nDatei: " . $this->getFile() . "\n", 
                FILE_APPEND);
        
        parent::__construct($message, $code);
    }
    
    public function myMessage(){
        return $this->errorMsg[$this->code];
    }
}