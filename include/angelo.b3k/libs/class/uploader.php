<?php
class Upload {
    
    protected $core;
    
    protected $path;
    
    protected $name;
    
    protected $pattern;
    
    protected $status;
    
    protected $errors = array();


    public function __construct($object) {
        $this->core = $object;
        return $this;
    }

    public function path($path){
        $this->path = (string) $path;
        return $this;
    }
    
    public function name($name){
        $this->name = (string) $name;
        return $this;
    }
    
    public function type(){
        $this->pattern = (string) "/(\.". implode('|\.', func_get_args()).")/i";
        return $this;
    }
    
    public function init(){
        if( isset( $_FILES ) ) {
            foreach($_FILES as $key => $val){

                if( $val['size'] < 0 ){
                    $this->errors[] = 'die Datei ist Leer';
                    return null;
                }

                $status = @preg_match($this->pattern, $val['name'], $res);

                if( !empty( $this->type ) && !$status ){
                    $this->errors[] = 'falscher datei type!';
                    return null;
                }


                if(empty($this->name) ){
                    $this->name = time() . $res[1];
                } else {
                    $this->name .= $res[1];
                }

                if(move_uploaded_file($val['tmp_name'], $this->path.$this->name)){
                    $this->status = true;
                    return $this->path.$this->name;
                } else {
                    return null;
                }

            }
        }
    }
    
    public function status(){
        return $this->status;
    }
    
    public function errors(){
        return implode('', $this->errors);
    }
} 
?>