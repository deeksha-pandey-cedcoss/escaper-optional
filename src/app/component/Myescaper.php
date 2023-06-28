<?php
// escaper class
use Phalcon\Escaper;    

class Myescaper {

    public $escaper;

    public function __construct()
    {
        $this->escaper=new Escaper();
    }
    
    public function sanitize($data)
    {
    
       return $this->escaper->escapeHtml($data);
    }
}