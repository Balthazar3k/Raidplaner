<?php
class Header{
    
    protected $parse;

    protected $headers_html = array(
        'css' => '<link  type="text/css" href="include/raidplaner/libs/%s" rel="stylesheet" />',
        'js' => '<script type="text/javascript" src="include/raidplaner/libs/%s" ></script>'
    );
    
    public function set($string){
        preg_match('/\.(css|js)/', $string, $res);
        $this->parse[strstr($string,'/', true)][] = sprintf($this->headers_html[$res[1]], $string);
    }
    
    public function get(){
       global $ILCH_HEADER_ADDITIONS;
       
       $header = array();
       
       foreach(func_get_args() as $lib){
           $header[] = implode("\n\t", $this->parse[$lib]);
       }
       
       $ILCH_HEADER_ADDITIONS .= implode("\n\t", $header);
    }
}

