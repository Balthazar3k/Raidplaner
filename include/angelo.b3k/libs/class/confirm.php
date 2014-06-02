<?php
class Confirm {
    
    protected $core;
    
    protected $_message;    
    protected $_true;
    protected $_btnTrue;
    protected $_false;
    protected $_btnFalse;
    protected $_button;
    
    public function __construct($object) {
        $this->core = $object;
        return $this;
    }
    
    public function message($message){
        $this->_message = (string) $message;
        return $this;
    }
    
    public function onTrue($url, $btn = 'Löschen') {
        $this->_true = (string) $url;
        $this->_btnTrue = htmlentities($btn);
        return $this;
    }
    
    public function onFalse($url, $btn = 'Abbrechen'){
        $this->_false = (string) $url;
        $this->_btnFalse = htmlentities($btn);
        return $this;
    }
    
    public function html($title = '') {

        $attr = array(
            'data-true' => $this->_true,
            'data-false' => $this->_false,
        );
        
        return '
            <div id="dialog-confirm" title="'.htmlentities($title).'" '. $this->core->func()->setAttr($attr).'>
                '.htmlentities($this->_message).'
            </div>
        ';
    }
    
    public function panel($title = '') {
        global $design, $menu;
        
        if( empty($design)){
            $design = new design ( 'Admins Area', 'Admins Area', 2 );
            $design->header();
        }
        
        if( empty($this->_false) ){
            $this->onFalse('javascript:history.back(1)');
        }
        
        $html = '
            <div class="col-lg-3"></div>
            <div class="col-lg-6">
                <div class="panel panel-info" '. $this->core->func()->setAttr($attr).'>
                    <div class="panel-heading"><b>'.htmlentities($title).'</b></div>
                    <div class="panel-body">
                        <i class="fa fa-info-circle fa-3x pull-left"></i> 
                        '.htmlentities($this->_message).'<br style="clear: both;" />
                    </div>
                    <div class="panel-footer">
                        <div class="btn-group btn-group-justified">
                            <a class="btn btn-success" href="'.$this->_true.'">'.$this->_btnTrue.'</a>
                            <a class="btn btn-warning" href="'.$this->_false.'">'.$this->_btnFalse.'</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3"></div>
            <br style="clear: both;" />
        ';
        

        echo $html;
        $design->footer();
        exit();

    }
}
?>