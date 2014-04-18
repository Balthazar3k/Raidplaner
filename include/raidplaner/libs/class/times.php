<?php
class Times{
    
    protected $raidplaner;
    
    public function __construct($object) {
        $this->raidplaner = $object;
        return $this;
    }
    
    public function get(){
        return $this->raidplaner->db()->select('*')->from('raid_zeit')->rows();
    }
    
    public function save($data, $id = false){
        if( $id ){
            $this->raidplaner->db()->update('raid_zeit')->fields($data)->where(array('id' => $id))->init();
        } else {
            $this->raidplaner->db()->insert('raid_zeit')->fields($data)->init();
        }
    }
    
    public function delete($id){
        return $this->raidplaner->db()->delete('raid_zeit')->where(array('id' => $id))->init();
    }
}
?>