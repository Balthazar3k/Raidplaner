<?php
class Header{
    
    protected $i = 0;
    protected $parse;
    protected $set = array();

    protected $headers_html = array(
        'css' => '<link id="%s" type="text/css" href="include/angelo.b3k/libs/%s" rel="stylesheet" />',
        'js' => '<script id="%s" type="text/javascript" src="include/angelo.b3k/libs/%s" charset="utf-8"></script>'
    );
    
    public function set($string){
        $this->i++;
        preg_match('/\.(css|js)/', $string, $res);
        $this->parse[strstr($string,'/', true)][] = sprintf($this->headers_html[$res[1]], $this->i, $string);
        return $this;
    }
    
    public function get(){
       global $ILCH_HEADER_ADDITIONS;
       
       $header = array();
       
       foreach(func_get_args() as $lib){
           $header[] = implode("\n\t", $this->parse[$lib]);
       }
       
       $ILCH_HEADER_ADDITIONS .= implode("\n\t", $header);
    }
    
    public function init(){
       $header = array();
       
       foreach(func_get_args() as $lib){
           $header[] = implode("\n\t", $this->parse[$lib]);
       }
       
       echo implode("\n", $header);
    }
    
    
}

