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
    
    protected $confirm;


    public function db(){
        if(empty($this->db)){
            $this->db = new Database();
        }
        
        return $this->db;
    }
    
    public function charakter($id = false){
        if(empty($this->charakter)){
            $this->charakter = new Charakter($this);
        } 
        
        if( $id ){
            $this->charakter->setId($id);
        } else {
            $this->charakter->setId(NULL);
        }
        
        return $this->charakter;      
    }
    
    public function permission(){
        if(empty($this->permission)){
            $this->permission = new Permission($this);
        }
        
        return $this->permission;
    }
    
    public function confirm(){
        if(empty($this->confirm)){
            $this->confirm = new Confirm($this);
        }
        
        return $this->confirm;
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

class Charakter {
    
    protected $raidplaner;
    
    protected $_id;
    
    public function __construct($object) {
        $this->raidplaner = $object;
        return $this;
    }
    
    public function setId($id){
        $this->_id = (int) $id;
    }
    
    public function save($data) {

        $status = array();
        
        $ID = $this->raidplaner->db()->select('id')
                ->from('raid_chars')
                ->where(array('user' => $_SESSION['authid'], 'id' => $this->_id))
                ->cell();
        
        if( $ID ){
            $status[] = (bool) $this->raidplaner->db()->update('raid_chars')->fields($data)->where(array('id' => $this->_id ))->init();
        } else {
            $status[] = (bool) $this->raidplaner->db()->insert('raid_chars')->fields($data)->init();
        }
        

        if(in_array( false, $status)){
            return false;
        } else {
            return true;
        }
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
    
    public function name(){
        return $this->raidplaner->db()
                ->select('name')
                ->from('raid_chars')
                ->where(array('id' => $this->_id))
                ->cell();
    }
    
    public function get(){
        $res = $this->raidplaner->db()
                ->select('*')
                ->from('raid_chars')
                ->where(array('id' => $this->_id))
                ->row();
        
        if( !$res ){
            return array();
        }
        
        return $res;
    }
    
    public function form($title, $pfad, $charakter = array()){
        global $allAr;
        
        $tpl = new tpl ('raid/CHARS_EDIT_CREAT.htm');
        
        $row['title'] = $title;
        $row['pfad'] = $pfad;
        
        $row['name'] = $charakter['name'];
        $row['level'] = $charakter['level'];
        $row['rassen'] = drop_down_menu("prefix_raid_rassen" , "rassen", $charakter['rassen'], "");
        $row['klassen'] = drop_down_menu("prefix_raid_klassen" , "klassen", $charakter['klassen'], "");
        $row['spz'] = "Klasse w&auml;hlen!";
        $row['skillgruppe'] = skillgruppe(1, $charakter['skillgruppe']);
        $row['warum']  = $charakter['warum'];
        $row['realm'] = $allgAr['realm'];

        $tpl->set_ar_out( $row, 0 );
    }
}

class Confirm {
    
    protected $raidplaner;
    
    protected $_message;    
    protected $_true;
    protected $_false;
    protected $_button;
    
    public function __construct($object) {
        $this->raidplaner = $object;
        return $this;
    }
    
    public function message($message){
        $this->_message = (string) $message;
        return $this;
    }
    
    public function onTrue($url) {
        $this->_true = (string) $url;
        return $this;
    }
    
    public function onFalse($url){
        $this->_false = (string) $url;
        return $this;
    }
    
    public function html($title = '') {

        $attr = array(
            'data-true' => $this->_true,
            'data-false' => $this->_false,
        );
        
        return '
            <div id="dialog-confirm" title="'.$title.'" '. $this->raidplaner->setAttr($attr).'>
                '.$this->_message.'
            </div>
        ';
    }
}

class Permission {
    
    protected $raidplaner;
    
    protected $update;
    protected $delete;
    
    public function __construct($object) {
        $this->raidplaner = $object;
        
        /* Permissions for Deleting */
        $this->update = array(
            'charakter' => array(
                'permission' => ( $_SESSION['charrang'] >= 13 || is_admin() ), 
                'message' => 'Sie haben nicht die n&ouml;tigen Rechte!'
            )
        );
        
        /* Permissions for Deleting */
        $this->delete = array(
            'charakter' => array(
                'permission' => ( $_SESSION['charrang'] >= 13 || is_admin() ), 
                'message' => 'Sie haben nicht die n&ouml;tigen Rechte!'
            )
        );
        
        return $this;
    }
    
    public function update($key, &$message = NULL) {
        $message = $this->update[$key]['message'];
        return $this->update[$key]['permission'];
    }

    public function delete($key, &$message = NULL) {
        $message = $this->delete[$key]['message'];
        return $this->delete[$key]['permission'];
    }
    
}
?>