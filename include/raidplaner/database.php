<?php

/* 
 * Copyright: Balthazar3k.funpic.de 2014
 * Modue: Raidplaner 1.1
 */

class Database {
    
    protected $type;
    
    protected $_sql;
    
    public $_fields = array();
    public $_from;
    public $_where;
    public $_limit;


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
    
    public function init(){
        $this->maskedValues();
        if(!empty($this->type)) {
            $method = $this->type;
            $sql = Construct::$method($this);
            db_query($sql);
        }
    }
    
    public function maskedValues(){
        array_walk($this->_fields, function(&$field){
           $field = mysql_real_escape_string($field);
        });
    }
    
}

class Construct
{
    public static function update($data)
    {
        $sql = 'UPDATE `'.$data->_from.'`';
        $sql .= ' SET '. implode(', ', self::getFields($data->_fields));
        $sql .= ' WHERE '. implode('AND ', self::getFields($data->_where));
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