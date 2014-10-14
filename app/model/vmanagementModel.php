<?php

class vmanagementModel extends Model
{
    public function get_event_list($limit = null)
    {
        $sql = "SELECT * FROM event";
        if (isset($limit))
            $sql .= " ORDER BY date ASC LIMIT $limit";
        
        $result = $this->_db->query($sql);
        return $result->fetchAll(PDO::FETCH_OBJ);
    }
    
    public function get_event_list_by($search, $filter) {
        if ($filter == 'date') {
            $result = $this->_db->query("SELECT * FROM event WHERE date='$search'");
        }
        elseif ($filter == 'name') {
            $result = $this->_db->query("SELECT * FROM event WHERE event_name LIKE '%$search%'");
        }
        elseif ($filter == 'event type') {
            $result = $this->_db->query("SELECT * FROM event WHERE event_type_id='$search'");
            
        }
        
        return $result->fetchAll(PDO::FETCH_OBJ);
    }
    
    /**
     * Function description of the event obtained from the evvent id list
     * @param $id Id of the event which is going to be returned
     */
    public function get_event_details($id)
    {
        $result = $this->_db->query("
                SELECT * FROM event
                INNER JOIN user ON user.user_id = event.user_id
                INNER JOIN event_type ON event.event_type_id = event_type.event_type_id
                INNER JOIN skill_type ON skill_type.skill_type_id = event.skills_required_id	
                WHERE event_id=$id");
        return $result->fetchAll(PDO::FETCH_OBJ);
    }
    
    // this is to insert a new row in a table or insert function
    /**
     * This is a function description
     */
    public function create_event($name, $date, $description, $capacity, $skills_required,$event_type_id, $user_id)
    {
        $this->_db->query("INSERT INTO event (event_name, date, description, capacity, skills_required_id, event_type_id, user_id)
                          VALUES ('$name', '$date', '$description', $capacity, $skills_required, $event_type_id, $user_id)");
        
                
    }
    
    public function modify_event($id,$name,$date,$description,$capacity,$skills_required,$event_type_id,$user_id)
    {
        $sql = "UPDATE event SET 
                            event_name='$name',
                            date='$date',
                            description='$description',
                            capacity=$capacity,
                            skills_required_id=$skills_required,
                            event_type_id=$event_type_id,
                            user_id=$user_id
                             WHERE event_id=$id";
        $this->_db->query($sql);      

                             
    }
    public function get_skill_type()
    {
        $result = $this->_db->query("SELECT * FROM skill_type");
        return $result->fetchAll(PDO::FETCH_OBJ);
        
    }
    
    public function get_skill_details($skill_id)
    {
        $result = $this->_db->query("SELECT * FROM skill_type WHERE skill_type_id=$skill_id");
        return $result->fetch(PDO::FETCH_OBJ);
    }
   /** public function enable_skill_audit($skill_type_name, $level, $user_type)
    {
        $this->_db->query("INSERT INTO skill_type (skill_name, skill_level, user_type_id) 
            VALUES ('$skill_type_name', $level, $user_type)");
    }*/
    public function add_skill($skill_type_name, $level, $user_id) {
             
        $this->_db->query("INSERT INTO skill_type (skill_type_name, level, user_id) 
                        VALUES ('$skill_type_name', '$level', '$user_id')");
    }
    public function add_skill_required($skill_required_id, $skill_type_name) {
        $this->_db->query("INSERT INTO skills_required(skill_required, skill_name)
                        VALUES ('$skill_required_id', '$skill_type_name')");
    }
   public function modify_skill($id, $name, $level, $user_id){
       $this->_db->query("UPDATE skill_type SET
                          skill_type_name='$name',
                          level=$level,
                          user_id=$user_id
                         WHERE skill_type_id=$id");
   }
   public function delete_enable_skill_audit($skill_id)
   {
      $this->_db->query("DELETE FROM skill_type WHERE skill_type_id=$skill_id");
   }
   public function delete_event($event_id)
   {
       $this->_db->query("DELETE FROM event WHERE event_id=$event_id");
   }
   public function add_user ($user_id, $user_name){
       $this->_db->query("INSERT INTO event_details (user_id, user_name) VALUES
           ('$user_id', '$user_name')");
   }
   public function get_event_users($id)
   {
       $result = $this->_db->query("SELECT * FROM eventxuser INNER JOIN user ON eventxuser.user_id = user.user_id
       WHERE event_id = $id");
       return $result->fetchAll(PDO::FETCH_OBJ);
   }
   public function get_skill_type_user()
   {
       $result = $this->_db->query("SELECT * FROM skill_type 
               INNER JOIN user ON skill_type.user_id = user.user_id
                ");
       return $result->fetchAll(PDO::FETCH_OBJ);
   }
   public function get_user_list(){
   
       $result = $this->_db->query("SELECT * FROM user");
   
        return $result->fetchAll(PDO::FETCH_OBJ);
    }

}
?>
