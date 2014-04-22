<?php

class Event {
    
    protected $raidplaner;
    protected $_id;
    
    public function __construct($object) {
        $this->raidplaner = $object;
        return $this;
    }
    
    private function db($table = NULL){
        return $this->raidplaner->db($table);
    }
    
    private function standart_sql(){
        return "
            SELECT
                a.id, a.title, a.txt, a.series, a.weekdays, a.inv, a.pull, a.end, a.updated, a.created, a.creator,
                b.id AS status_id, b.statusmsg AS status_name, b.color AS status_color,
                c.id AS leader_id, c.name AS leader_name,
                d.id AS group_id, d.gruppen AS group_name, d.regeln AS group_rules,
                e.id AS dungeon_id, e.small AS dungeon_small, e.name AS dungeon_name, e.grpsize AS dungeon_size,
                f.id AS loot_id, f.loot AS loot_name,
                g.id AS creator_id, g.name AS creator_name
            FROM prefix_raid_raid AS a
                LEFT JOIN prefix_raid_statusmsg AS b ON b.id = a.status
                LEFT JOIN prefix_raid_chars AS c ON c.id = a.leader
                LEFT JOIN prefix_raid_gruppen AS d ON d.id = a.group
                LEFT JOIN prefix_raid_inzen AS e ON e.id = a.dungeon
                LEFT JOIN prefix_raid_loot AS f ON f.id = a.loot
                LEFT JOIN prefix_raid_chars AS g ON g.id = a.creator
        ";
    }

    public function setId($id){
        $this->_id = (int) $id;
        return $this;
    }
    
    public function save($res){
        
    }
    
    public function get(){
        if( $this->_id ){
            return $this->db()->queryRow(
                $this->standart_sql()."
                WHERE a.id = '".$this->_id."'    
            ");     
        } else {
            trigger_error('no Event ID');
        }
    }
    
    public function delete(){
        
    }
    
    public function getList(){
        
    }
    
    public function calendar(){
        
    }
    
    public function form($title, $path){
        $data = array();
        
        $data['form_path'] = $path;
        $data['form_title'] = $title;
        
        $data['status'] = $this->db('raid_statusmsg')
                ->select('id', 'statusmsg')
                ->rows();
        
        $data['leader'] = $this->db('raid_chars')
                ->select('id', 'name')
                ->where('rang', 10)
                ->order(array('rang' => 'DESC'))
                ->rows();
        
        $data['dungeon'] = $this->db('raid_inzen')
                ->select('id', 'name')
                ->rows();
        
        $data['group'] = $this->db('raid_gruppen')
                ->select('id', 'gruppen')
                ->rows();
        
        $data['times'] = $this->db('raid_zeit')
                ->select('id', 'weekday', 'start', 'inv', 'pull')
                ->rows();
        
        $this->raidplaner->smarty()
                ->assign('data', $data)
                ->assign('event', $this->get())
                ->display('event_form.tpl');           
    }
}


?>