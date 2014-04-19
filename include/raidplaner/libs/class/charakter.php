<?php

class Charakter {
    
    protected $raidplaner;
    
    protected $_id;
    protected $_uid;


    public function __construct($object) {
        $this->raidplaner = $object;
        return $this;
    }
    
    public function setId($id){
        $this->_id = (int) $id;
    }
    
    public function save($data) {

        $status = array();
        
        $ID = $this->raidplaner->db()->select('id')
                ->from('raid_chars')
                ->where(array('user' => $_SESSION['authid'], 'id' => $this->_id))
                ->cell();
        
        if( $ID ){
            $status[] = (bool) $this->raidplaner->db()->update('raid_chars')->fields($data['charakter'])->where(array('id' => $this->_id ))->init();
            $status[] = (bool) $this->raidplaner->db()->delete('raid_zeit_charakter')->where(array('cid' => $ID ))->init();
            foreach( array_keys( $data['times']) as $timeID ){
                $status[] = (bool) $this->raidplaner->db()->insert('raid_zeit_charakter')->fields(array('zid' => $timeID, 'cid' => $ID))->init();
            }
        } else {
            $status[] = (bool) $this->raidplaner->db(1)->insert('raid_chars')->fields($data['charakter'])->init();
            $charakter_id = $this->raidplaner->db()->select('id')->from('raid_chars')->where(array('name' => $data['charakter']['name']))->cell();
            foreach( array_keys( $data['times']) as $timeID ){
               $status[] = (bool) $this->raidplaner->db()->insert('raid_zeit_charakter')->fields(array('zid' => $timeID, 'cid' => $charakter_id))->init();
            }
        }
        

        if(in_array( false, $status)){
            return false;
        } else {
            return true;
        }
    }

    public function delete($cid) {

        $status = array();
        $status[] = (bool) $this->raidplaner->db()->delete('raid_chars')->where(array('id' => $cid))->init();
        $status[] = (bool) $this->raidplaner->db()->delete('raid_dkp')->where(array('cid' => $cid))->init();
        $status[] = (bool) $this->raidplaner->db()->delete('raid_kalender')->where(array('cid' => $cid))->init();
        $status[] = (bool) $this->raidplaner->db()->delete('raid_anmeldung')->where(array('char' => $cid))->init();

        if(in_array( false, $status)){
            return false;
        } else {
            return true;
        }
    }
    
    public function owner($id){
        return $this->raidplaner->db()
                ->select('id')
                ->from('raid_chars')
                ->where(array('id' => $id, 'user' => $_SESSION['authid']))
                ->cell();
    }
    
    public function name(){
        return $this->raidplaner->db()
                ->select('name')
                ->from('raid_chars')
                ->where(array('id' => $this->_id))
                ->cell();
    }
    
    public function rank(){
        return $this->raidplaner->db()
                ->select('rank')
                ->from('raid_chars')
                ->where(array('id' => $this->_id))
                ->cell();
    }
    
    public function get(){
        
        if( empty($this->_id)){
            trigger_error('need Charakter id');
        }
        
        $charakter = $this->raidplaner->db(0)->queryRow('
            SELECT 
                a.id, a.name, a.level, a.s1, a.s2, a.warum, a.skillgruppe, a.regist,
                b.id as class_id, b.klassen as class_name,  
                d.id as rank_id, d.rang as rank_name, 
                f.id as user_id, f.name AS user_name, 
                e.id as race_id, e.rassen as race_name
             FROM prefix_raid_chars AS a 
                LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
                LEFT JOIN prefix_raid_rang AS d ON a.rang = d.id 
                LEFT JOIN prefix_raid_rassen AS e ON a.rassen = e.id 
                LEFT JOIN prefix_user AS f ON a.user = f.id 
             WHERE a.id = \''.$this->_id.'\'
            LIMIT 1
        ');
         
        $this->_uid = $charakter['user_id'];
        
        $charakter['times'] = $this->raidplaner->db()
                ->select('zid')
                ->from('raid_zeit_charakter')
                ->where(array('cid' => $this->_id))
                ->key2int();
        
        return $charakter;
    }
    
    public function own(){
        if( $this->_id ){
            $this->_uid = $this->raidplaner->db()
                ->select('user')
                ->from('raid_chars')
                ->where(array('id' => $this->_id))
                ->cell();

            return $this->raidplaner->db()->queryRows('
                SELECT 
                    a.id, a.name, a.level, a.s1, a.s2,
                    b.id as class_id, b.klassen as class_name,  
                    d.id as rank_id, d.rang as rank_name,  
                    e.id as race_id, e.rassen as race_name
                 FROM prefix_raid_chars AS a 
                    LEFT JOIN prefix_raid_klassen AS b ON a.klassen = b.id 
                    LEFT JOIN prefix_raid_rang AS d ON a.rang = d.id 
                    LEFT JOIN prefix_raid_rassen AS e ON a.rassen = e.id 
                 WHERE a.user = \''.$this->_uid.'\'
            ');
        } else {
            return array();
        }
    }

    public function form($title, $pfad, $charakter_id = false){
        global $allgAr;
        
        if( $charakter_id ){
            $charakter = $this->get();
        }
        
        $tpl = $this->raidplaner->smarty();
        
        $data['title'] = $title;
        $data['path'] = $pfad;

        $data['rassen'] = $this->raidplaner->db()
                ->select('*')
                ->from('raid_rassen')
                ->rows();
        
        $data['klassen'] = $this->raidplaner->db()
                ->select('id', 'klassen')
                ->from('raid_klassen')
                ->rows();
              
        $data['spz'] = classSpecialization($charakter['class_id'], $charakter['s1'], $charakter['s2']);
        $data['skillgruppe'] = skillgruppe(1, $charakter['skillgruppe']);
        $data['realm'] = $allgAr['realm'];
        
        $data['times'] = $this->raidplaner->db()
                ->select('*')
                ->from('raid_zeit')
                ->rows();

        $tpl->assign('data', $data);
        $tpl->assign('charakter', $charakter);
        $tpl->display('charakter_form.tpl');
    }
    
    public function details(){
        $charakter = $this->get();
        
        $tpl = $this->raidplaner->smarty();
        $tpl->assign('charakter', $charakter);
        $tpl->assign('ownCharakters', $this->own());
        $tpl->assign('times', $this->raidplaner->times()->get());
        $tpl->display('charakter_details.tpl');
    }
}
?>