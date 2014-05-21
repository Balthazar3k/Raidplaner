<?php

/* 
 * Copyright: Balthazar3k.funpic.de 2014
 * Modue: Raidplaner 1.1
 */

class Database {
    
    protected $type;
    protected $duplicate = false;
    
    protected $_sql;

    public $_select;
    public $_fields;
    public $_from;
    public $_where;
    public $_order;
    public $_limit;
    
    public function status(){
        $status = array(
            'Type' => $this->type,
            'DuplicateSwitch' => $this->duplicate,
            'Select' => $this->_select,
            'From' => $this->_from,
            'Fields' => $this->_fields,
            'Where' => $this->_where
        );
        
        arrPrint($status);
    }
    
    public function singel(){
        $this->duplicate = true;        
        return $this;
    }
    
    private function setDuplicate($sql){
        if( $this->duplicate ){
            $hash = md5($sql);
            $_SESSION['db'][$hash] = 1;
        }
    }
    
    private function isDuplicate($sql){
        if( $this->duplicate ){
            if( isset($_SESSION['db'][md5($sql)]) ){
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
    
    private function db_query($sql){
        if( $this->isDuplicate($sql) ) {
           $this->setDuplicate($sql);
           return db_query($sql);
        }
    }
    
    private function db_result($res){
        return db_result($res, 0);
    }
    
    public function from($from) 
    {
        $this->_from = (string) 'prefix_'.$from;
        return $this;
    }

    public function fields($param) 
    {
        $this->_fields = (array) $param;
        return $this;
    }
    
    public function where($param, $val = NULL) 
    {
        if( is_array($param) ){
            $this->_where = (array) $param;
        } else {
            $this->_where = array($param => $val);
        }
        return $this;
    }
    
    public function order($param) 
    {
        $this->_order = (array) $param;
        return $this;
    }
    
    public function select()
    {
        $this->type = substr(strrchr(__METHOD__, ':'), 1);
        $this->_select = (array) func_get_args();
        return $this;
    }
    
    public function update($from)
    {
        $this->type = substr(strrchr(__METHOD__, ':'), 1);
        $this->_from = (string) 'prefix_'.$from;
        return $this;
    }
    
    public function insert($from)
    {
        $this->type = substr(strrchr(__METHOD__, ':'), 1);
        $this->_from = (string) 'prefix_'.$from;
        return $this;
    }
    
    public function delete($from)
    {
        $this->type = substr(strrchr(__METHOD__, ':'), 1);
        $this->_from = (string) 'prefix_'.$from;
        return $this;
    }
    
    public function init(){
        $this->maskedValues();
        if(!empty($this->type)) {
            $method = $this->type;
            $sql = Construct::$method($this);
            $this->_sql[] = $sql;
            return $this->db_query($sql);
        }
    }
    
    public function query($sql){
        $this->_sql[] = $sql;
        return $this->db_query($sql);
    }
    
    public function queryRows($sql){
        $this->_sql[] = $sql;
        $res = $this->db_query($sql);
        return $this->rows($res);
    }

    public function queryRow($sql){
        $this->_sql[] = $sql;
        $res = $this->db_query($sql);
        return $this->row($res);
    }
    
    public function queryCell($sql){
        $this->_sql[] = $sql;
        $res = $this->db_query($sql);
        return $this->db_result($res);
    }
    
    public function getQuery(){
        $this->maskedValues();
        if(!empty($this->type)) {
            $method = $this->type;
            return Construct::$method($this);   
        }
    }
    
    public function row($res = null){
        if( $res == null ){
            $res = $this->init();
        }
        
        $return = db_fetch_assoc($res);
        $this->_sql[] = $return;
        return $return;
    }
    
    public function rows($res = null){
        $rows = array();
        
        if( $res == null ){
            $res = $this->init();
        }
        
        while( $row = db_fetch_assoc($res) ){
            $rows[] = $row;
        }
        
        $this->_sql[] = $rows;
        
        return $rows;
    }
    
    public function key2int(){
        $rows = array();
        $res = $this->init();
        while( $row = mysql_fetch_assoc($res) ){
            $keys = array_keys($row);
            $rows[] = $row[$keys[0]];
        }
        $this->_sql[] = $rows;
        return $rows;
    }
    
    public function cell($select = 0){
        $res = $this->row();
        $return = $res[$this->_select[$select]];
        $this->_sql[] = $return;
        return $return;
    }
    
    public function maskedValues(){
        array_walk($this->_fields, function(&$field){
           $field = mysql_real_escape_string($field);
        });
    }
    
    public function reset(){
        $this->duplicate = false;
        $this->_select = array();
        $this->_fields = array();
        $this->_from = NULL;
        $this->_where = NULL;
        $this->_limit = NULL;
    }
    
    public function getSql(){
        print_r($this->_sql);
    }
    
}

class Construct
{
    public static function select($data)
    {
        $sql = 'SELECT '. ( $data->_select[0] == '*' ? '*' : implode(', ', self::getSelect($data->_select)));
        $sql .= ' FROM '. $data->_from;
        $sql .= ( empty($data->_where) ? '':' WHERE '. implode(' AND ', self::getFields($data->_where)) ) ;
        $sql .= ( empty($data->_order) ? '':' ORDER BY '. implode(', ', self::getOrder($data->_order)) ) ;
        $sql .= ';';
        return $sql;
                
    }
    
    public static function update($data)
    {
        $sql = 'UPDATE `'.$data->_from.'`';
        $sql .= ' SET '. implode(', ', self::getFields($data->_fields));
        $sql .= ' WHERE '. implode(' AND ', self::getFields($data->_where));
        $sql .= ';';
        return $sql;
                
    }
    
    public static function insert($data)
    {
        $sql = 'INSERT INTO `'.$data->_from.'`';
        $sql .= ' ('. implode(', ', self::getFieldKeys($data->_fields)).')';
        $sql .= ' VALUES( '. implode(', ', self::getFieldValues($data->_fields)).')';
        $sql .= ';';
        return $sql;
                
    }
    
    public static function delete($data)
    {
        $sql = 'DELETE FROM `'.$data->_from.'`';
        $sql .= ' WHERE '. implode(' AND ', self::getFields($data->_where));
        $sql .= ';';
        return $sql;
                
    }
    
    public static function getSelect($attributes)
    {
        if( is_array($attributes) && count($attributes) > 0 ){
            $attr = array();
            foreach( $attributes as $key => $value){
                $attr[] = '`'.$value.'`';
            }
            return $attr;
        }
    }
    
    public static function getFields($attributes)
    {
        if( is_array($attributes) && count($attributes) > 0 ){
            $attr = array();
            foreach( $attributes as $key => $value){
                $attr[] = '`'.$key.'`="'.$value.'"';
            }
            return $attr;
        }
    }
    
    public static function getFieldKeys($attributes)
    {
        if( is_array($attributes) && count($attributes) > 0 ){
            $attr = array();
            foreach( $attributes as $key => $value){
                $attr[] = '`'.$key.'`';
            }
            return $attr;
        }
    }
    
    public static function getFieldValues($attributes)
    {
        if( is_array($attributes) && count($attributes) > 0 ){
            $attr = array();
            foreach( $attributes as $value){
                $attr[] = '"'.$value.'"';
            }
            return $attr;
        }
    }
    
    public static function getOrder($attributes)
    {
        if( is_array($attributes) && count($attributes) > 0 ){
            $attr = array();
            foreach( $attributes as $key => $value){
                $attr[] = '`'.$key.'` '.$value.'';
            }
            return $attr;
        }
    }
}
?>