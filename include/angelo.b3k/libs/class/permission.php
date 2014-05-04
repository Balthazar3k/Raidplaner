<?php
class Permission {
    
    protected $core;
    
    protected $create;
    protected $update;
    protected $delete;
    
    public function __construct($object) {
        $this->core = $object;
        
        /* Permissions for Creating */
        $this->create = array(
            'times' => array(
                'permission' => ( $_SESSION['charrang'] >= 13 || is_admin() ), 
                'message' => 'Sie haben nicht die n&ouml;tigen Rechte um die Zeiten zu bearbeiten!'
            )
        );
        
        /* Permissions for Updateing */
        $this->update = array(
            'charakter' => array(
                'permission' => ( $_SESSION['charrang'] >= 13 || is_admin() ), 
                'message' => 'Sie haben nicht die n&ouml;tigen Rechte!'
            ),
            'Dungeons' => array(
                'permission' => ( $_SESSION['charrang'] >= 13 || is_admin() ), 
                'message' => 'Sie haben nicht die n&ouml;tigen Rechte, um an den Dungeons &auml;nderungen vorzunehmen!'
            ),
            'Classes' => array(
                'permission' => ( $_SESSION['charrang'] >= 13 || is_admin() ), 
                'message' => 'Sie haben nicht die n&ouml;tigen Rechte, um an den Klassen &auml;nderungen vorzunehmen!'
            ),
            'application_class' => array(
                'permission' => ( $_SESSION['charrang'] >= 10 || is_admin() ), 
                'message' => 'Sie haben nicht die n&ouml;tigen Rechte, um an den Dungeons &auml;nderungen vorzunehmen!'
            )
        );
        
        /* Permissions for Deleting */
        $this->delete = array(
            'charakter' => array(
                'permission' => ( $_SESSION['charrang'] >= 13 || is_admin() ), 
                'message' => 'Sie haben nicht die n&ouml;tigen Rechte!'
            ),
            'times' => array(
                'permission' => ( $_SESSION['charrang'] >= 13 || is_admin() ), 
                'message' => 'Sie haben nicht die n&ouml;tigen Rechte um die Zeiten zu L&ouml;schen!'
            ),
            'fast' => array(
                'permission' => ( is_admin() ), 
                'message' => ''
            )
        );
        
        return $this;
    }
    
    public function create($key, &$message = NULL) {
        $message = $this->create[$key]['message'];
        return $this->create[$key]['permission'];
    }
    
    public function update($key, &$message = NULL) {
        $message = $this->update[$key]['message'];
        return $this->update[$key]['permission'];
    }

    public function delete($key, &$message = NULL) {
        $message = $this->delete[$key]['message'];
        return $this->delete[$key]['permission'];
    }
    
    public function stay($mode, $key){
        global $design;
        if( !$this->$mode($key, $message) ){
            echo $message;
            $design->footer();
            exit();
        }
    }
    
}
?>