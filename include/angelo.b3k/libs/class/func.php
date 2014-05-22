<?php
class Func {
    
    protected $core;
    
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
    
    /**
     * View Arrays, Objects or Strings
     * 
     * @param mixed $vars
     */
    
    public function ar()
    {
        foreach(func_get_args() as $i => $res){
            ?><h1><?=$i?></h1><pre class="func-ar"><?php
            print_r($res);
            ?><hr></pre><?php
        }
    }
    
    /**
     * Transform Arrays
     * 
     * @param array
     */
    
    public function transformArray($currentArray)
    {
        $newArray = array();
        $this->ar($currentArray);
        foreach($currentArray as $id => $ar){
            foreach( $ar as $key => $val ){
                $newArray[$key][] = $val;
            }
        }
        
        return $newArray;
    }  
}
?>