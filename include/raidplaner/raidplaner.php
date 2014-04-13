<?php

/* 
 * Copyright: Balthazar3k.funpic.de 2014
 * Modue: Raidplaner 1.1
 */

require_once('include/raidplaner/database.php');

class Raidplaner {
    
    protected $db;
    
    public function db(){
        $this->db = new Database();
        return $this->db;
    }
}
?>