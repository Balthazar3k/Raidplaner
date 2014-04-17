<?php

/* 
 * Copyright: Balthazar3k.funpic.de 2014
 * Modue: Raidplaner 1.1
 */

class Database {
    
    protected $type;
    
    protected $_sql;
    
    public $_select = array();
    public $_fields = array();
    public $_from;
    public $_where;
    public $_limit;
    
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
    
    public function where($param) 
    {
        $this->_where = (array) $param;
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
            $this->reset();
            return db_query($sql);
        }
    }
    
    public function getQuery(){
        $this->maskedValues();
        if(!empty($this->type)) {
            $method = $this->type;
            return Construct::$method($this);   
        }
    }
    
    public function row(){
       return db_fetch_assoc($this->init());
    }
    
    public function cell($select = 0){
        $res = $this->row();
        return $res[$this->_select[$select]];
    }
    
    public function maskedValues(){
        array_walk($this->_fields, function(&$field){
           $field = mysql_real_escape_string($field);
        });
    }
    
    public function reset(){
        $this->_select = array();
        $this->_fields = array();
        $this->_from = NULL;
        $this->_where = NULL;
        $this->_limit = NULL;
    }
    
}

class Construct
{
    public static function select($data)
    {
        $sql = 'SELECT '. ( $data->_select[0] == '*' ? '*' : implode(', ', self::getSelect($data->_select)));
        $sql .= ' FROM '. $data->_from;
        $sql .= ( empty($data->_where) ? '':' WHERE '. implode(' AND ', self::getFields($data->_where)) ) ;
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
}
?>