<?php

/* 
 * Copyright: Balthazar3k.funpic.de 2014
 * Module: Core
 */

class Core {
    
    protected $db;
    
    protected $pager;
    
    protected $permission;
    
    protected $confirm;
    
    protected $header;
    
    protected $smarty;
    
    protected $func;
    
    protected $upload;


    public function db($from = false){
        if(empty($this->db)){
            include('include/angelo.b3k/libs/class/database.php');
            $this->db = new Database();
        }
        
        $this->db->reset();
        if( $from != false){
            $this->db->from($from);
        }
        return $this->db;
    }
    
    public function pager(){
        if(empty($this->pager)){
            include('include/angelo.b3k/libs/class/pager.php');
            $this->pager = new Pager($this->db());
        }
        
        return $this->pager;
    }
    
    public function permission(){
        if(empty($this->permission)){
            include('include/angelo.b3k/libs/class/permission.php');
            $this->permission = new Permission($this);
        }
        
        return $this->permission;
    }
    
    public function confirm(){
        if(empty($this->confirm)){
            include('include/angelo.b3k/libs/class/confirm.php');
            $this->confirm = new Confirm($this);
        }
        
        return $this->confirm;
    }
    
    public function header(){
        if(empty($this->header)){
            include('include/angelo.b3k/libs/class/header.php');
            $this->header = new Header();
        }
        
        return $this->header;
    }
    
    public function smarty(){
        if(empty($this->smarty)){
            require_once('include/angelo.b3k/libs/smarty/Smarty.class.php');
            $this->smarty = new Smarty();
            $this->smarty
                ->addTemplateDir('include/angelo.b3k/templates/')
                ->addPluginsDir('include/angelo.b3k/libs/smarty/plugins/')
                ->setCompileDir('include/angelo.b3k/cache/templates_c/')
                ->setCacheDir('include/angelo.b3k/cache/');
        }
        
        return $this->smarty;
    }
    
    public function func(){
        if(empty($this->func)){
            include('include/angelo.b3k/libs/class/func.php');
            $this->func = new Func();
        }
        
        return $this->func;
    }
    
    public function upload(){
        if(empty($this->upload)){
            include('include/angelo.b3k/libs/class/uploader.php');
            $this->upload = new Upload($this);
        }
        
        return $this->upload;
    }
}
?>