<?php

/* 
 * Copyright: Balthazar3k.funpic.de 2014
 * Modue: Raidplaner 1.1
 */

class Pager {
    
    protected $_db;
    protected $_page;
    protected $_rowsperpage;
    protected $_numrows;
    
    public function __construct($db_object) {
        $this->_db = $db_object;
    }
    
    private function db() {
        return $this->_db;
    }
    
    public function limit($rowsperpage = 10, $page = 1){
        if( $this->isSession() ){
            return $_SESSION['shop']['pager']['pages'][$page];
        } else {
            $this->rowsperpage($rowsperpage);
            return '0,'.$this->_rowsperpage;
        }
    }
    
    public function isSession(){
        return is_array($_SESSION['shop']['pager']['pages']);
    }
    
    private function createSession(){
        $this->_numrows = $this->db()->queryCell("SELECT FOUND_ROWS();");
        $_SESSION['shop']['pager']['numrows'] = $this->_numrows;
        $this->creatSessionPages();
    }
    
    private function creatSessionPages(){
        $s = 0;
        $e = $this->_rowsperpage;
        $m = ceil($this->_numrows/$e);
        
        for( $i = 1; $i < $m+1; $i++ ){
            $_SESSION['shop']['pager']['pages'][$i] = $s.','.$e;
            $s += $this->_rowsperpage;
            $e += $this->_rowsperpage;
        } 
    }
    
    public function get(){
        $this->createSession();      
    }
    
    private function rowsperpage($num){
        $this->_rowsperpage = (integer) $num;
        return $this;
    }
}

