<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of recordModel
 *
 * @author Segun
 */
class recordModel extends Model{
    // this function insert Records into the log_record table
    public function addRecord($userID,$logEventID){
        $sql = "INSERT INTO log_record (user_id,date,log_event_id)
                VALUES ('$userID', TIMESTAMP(CURRENT_TIMESTAMP ) , '$logEventID');";
        
        $result = $this->_db->exec($sql);
        return $result;             
    }
    
    public function getLogStatusID($status){
        
        $sql = "SELECT log_event_id FROM log_event 
                 WHERE desc_short_name = '$status'";
        
        $logResult = $this->_db->query($sql);
        
        if ($logResult->rowCount() > 0)
              return $logResult->fetch(PDO::FETCH_OBJ);
        else
            return false;
    }
}

?>
