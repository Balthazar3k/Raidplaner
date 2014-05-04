<?php

/* 
 * Copyright: Balthazar3k.funpic.de 2014
 * Modue: Raidplaner 1.1
 */

class Core {
    
    protected $db;
    
    protected $permission;
    
    protected $confirm;
    
    protected $header;
    
    protected $smarty;


    public function db($from = false){
        if(empty($this->db)){
            include('include/raidplaner/libs/class/database.php');
            $this->db = new Database();
        }
        
        $this->db->reset();
        if( $from != false){
            $this->db->from($from);
        }
        return $this->db;
    }
    
    
    public function permission(){
        if(empty($this->permission)){
            include('include/raidplaner/libs/class/permission.php');
            $this->permission = new Permission($this);
        }
        
        return $this->permission;
    }
    
    public function confirm(){
        if(empty($this->confirm)){
            include('include/raidplaner/libs/class/confirm.php');
            $this->confirm = new Confirm($this);
        }
        
        return $this->confirm;
    }
    
    public function header(){
        if(empty($this->header)){
            include('include/raidplaner/libs/class/header.php');
            $this->header = new Header();
        }
        
        return $this->header;
    }
    
    public function smarty(){
        if(empty($this->smarty)){
            require_once('include/raidplaner/libs/smarty/Smarty.class.php');
            $this->smarty = new Smarty();
            $this->smarty
                ->addTemplateDir('include/raidplaner/templates/')
                ->addTemplateDir('include/templates/raid/')
                ->addPluginsDir('include/raidplaner/libs/smarty/plugins/')
                ->setCompileDir('include/raidplaner/cache/templates_c')
                ->setCacheDir('.include/raidplaner/cache');
        }
        
        return $this->smarty;
    }
    
    /**
     * change the array to a HTML Atributes string
     * 
     * @param array $attributes
     * @return string
     */
    
    public function setAttr($attributes)
    {
        if( is_Array($attributes) && count($attributes) > 0 ){
            $attr = array();
            foreach( $attributes as $key => $value){
                if( is_array($value) ){
                    $attr[] = $key . '="'.$this->setCSS($value).'"';
                } else {
                    $attr[] = $key . '="'.$value.'"';
                }
            }
            return implode(' ', $attr);
        }
    }
}
?>