<?php

class Event {
    
    protected $raidplaner;
    protected $_id;
    
    public function __construct($object) {
        $this->raidplaner = $object;
        return $this;
    }
    
    private function db(){
        return $this->raidplaner->db();
    }

    public function setId($id){
        $this->_id = (int) $id;
        return $this;
    }
    
    public function save($res){
        
    }
    
    public function get(){
        
    }
    
    public function delete(){
        
    }
    
    public function list(){
        
    }
    
    public function calendar(){
        
    }
    
    public function form(){
        
    }
}


?>