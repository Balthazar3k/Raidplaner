<?php
class Confirm {
    
    protected $core;
    
    protected $_message;    
    protected $_true;
    protected $_false;
    protected $_button;
    
    public function __construct($object) {
        $this->core = $object;
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
            <div id="dialog-confirm" title="'.$title.'" '. $this->core->setAttr($attr).'>
                '.$this->_message.'
            </div>
        ';
    }
}
?>