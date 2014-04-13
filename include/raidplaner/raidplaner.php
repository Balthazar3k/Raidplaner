<?php

/* 
 * Copyright: Balthazar3k.funpic.de 2014
 * Modue: Raidplaner 1.1
 */

require_once('include/raidplaner/database.php');

class Raidplaner {
    
    protected $db;
    
    protected $charakter;
    
    protected $permission;
    
    public function db(){
        $this->db = new Database();
        return $this->db;
    }
    
    public function charakter(){
        $this->charakter = new Charakter($this);
        return $this->charakter;
    }
    
    public function permission(){
        $this->permission = new Permission($this);
        return $this->permission;
    }
}

class Charakter {
    
    protected $raidplaner;
    
    public function __construct($object) {
        $this->raidplaner = $object;
        return $this;
    }
    
    public function set(){
        
    }

    public function delete($cid) {

        $status = array();
        $status[] = (bool) $this->raidplaner->db()->delete('raid_chars')->where(array('id' => $cid))->init();
        $status[] = (bool) $this->raidplaner->db()->delete('raid_dkp')->where(array('cid' => $cid))->init();
        $status[] = (bool) $this->raidplaner->db()->delete('raid_kalender')->where(array('cid' => $cid))->init();
        $status[] = (bool) $this->raidplaner->db()->delete('raid_anmeldung')->where(array('char' => $cid))->init();

        if(in_array( false, $status)){
            return false;
        } else {
            return true;
        }
    }
    
    public function owner($id){
        return $this->raidplaner->db()
                ->select('id')
                ->from('raid_chars')
                ->where(array('id' => $id, 'user' => $_SESSION['authid']))
                ->cell();
    }
}

class Permission {
    
    protected $raidplaner;
    
    protected $delete;
    
    public function __construct($object) {
        $this->raidplaner = $object;
        
        /* Permissions for Deleting */
        $this->delete = array(
            'charakter' => array(
                'permission' => ( $_SESSION['charrang'] >= 13 || is_admin() ), 
                'message' => 'Sie haben nicht die n&ouml;tigen Rechte!'
            )
        );
        
        return $this;
    }

    public function delete($key, &$message = NULL) {
        $message = $this->delete[$key]['message'];
        return $this->delete[$key]['permission'];
    }
    
}
?>