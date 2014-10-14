<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of imModel
 *
 * @author Segun
 */
class imModel extends Model{
    
    /******************* INSERT QUERIES STARTS HERE *****************/
    //This function creates a new message into the message table
    public function createNewMessage($sender,$to,$messageType,$message){
       $sql = "INSERT INTO message(to_user, message_type_id, content, sent_time, received) 
                VALUES ($to,$messageType,'$message',NOW(),'0');
        
                INSERT INTO user_message(user_id, message_id) 
                VALUES ($sender,LAST_INSERT_ID())";
        
        $result = $this->_db->exec($sql);
        return $result;
    }
    
    
    //This function save messages into the save_message table
    public function saveMessage($sender,$to,$message){
        $sql = "INSERT INTO save_message(user_id, to_user, content, saved_date) 
                VALUES ($sender, $to,'$message' , NOW());";
        
        $result = $this->_db->exec($sql);
        return $result;
    }
    
    
     //This function report message by user message
    public function reportMessage($user_message){
        $sql = "INSERT INTO report_message(user_message_id) 
                VALUES ($user_message,NOW())";
        
        $result = $this->_db->exec($sql);
        return $result;
    }
    
    /******************* UPDATE QUERIES STARTS HERE *****************/
    
    //This function updates received message
    public function updatetoMarkedMessage($message,$marked){
        $sql = "UPDATE message SET received=$marked 
                WHERE message_id = $message";
        $result = $this->_db->exec($sql);
        return $result;
    }
    
    //This function updates messages to received
    public function updatetoMarkedMessageByUserId($user_id,$marked){
        $sql = "UPDATE message SET received=$marked 
                WHERE to_user = $user_id AND received = 0";
        $result = $this->_db->exec($sql);
        return $result;
    }
    
    //This function updates messages to reported
    public function updateReportMessage($message){
        $sql = "UPDATE message SET reported= 1
                WHERE message_id = $message";
        $result = $this->_db->exec($sql);
        return $result;
    }
    
    
    /******************* DELETE QUERIES STARTS HERE *****************/
    //This function delete by message id
    public function deleteMessages($message){
        $sql = "DELETE FROM message WHERE message_id = $message";
        $result = $this->_db->exec($sql);
        return $result;
    }
    
    /******************* SELECT QUERIES STARTS HERE *****************/
    
    
    //This function get user by user id
     public function getUserById($user_id){
        $sql = "SELECT first_name , last_name , is_login , avatar FROM user
        WHERE user_id = $user_id";
        $result = $this->_db->query($sql);
        if ($result->rowCount() > 0)
            return $result->fetch(PDO::FETCH_OBJ);
        else
            return false;
        
    }
    
    public function getUserMessageByMessageId($message_id){
        $sql = "SELECT id FROM user_message 
                WHERE message_id =$message_id";
        $result = $this->_db->query($sql);
        if ($result->rowCount() > 0)
            return $result->fetch(PDO::FETCH_OBJ);
        else
            return false;
    }
    //This function gets user details by email
    public function getUserIdByEmail($email){
        
        $result = $this->_db->query("SELECT user_id FROM user WHERE email = '$email'"); 
        if ($result->rowCount() > 0)
               
            return $result->fetch(PDO::FETCH_OBJ);
        else
            return false;
        
    }
    
    //This function get all the inbox messages of a user
    public function fetchInboxMessagesByUserId($userid){
           $result = $this->_db->query("SELECT b.message_id, a.user_id, b.to_user, b.content, b.sent_time,UNIX_TIMESTAMP( b.sent_time ) AS unixtime,b.reported, b.received, c.avatar, c.first_name, c.last_name, c.is_login
                                        FROM user_message a
                                        LEFT JOIN message b ON a.message_id = b.message_id
                                        LEFT JOIN user c ON c.user_id = a.user_id
                                        WHERE b.to_user =$userid
                                        AND b.message_type_id =1
                                        ORDER BY b.sent_time DESC
                                    "); 
            return $result->fetchAll(PDO::FETCH_OBJ);
    }
    
    //This function get all the messages sent by a user
    public function fetchSentMessagesByUserId($userid){
        $result = $this->_db->query("SELECT b.message_id, a.user_id, b.to_user, b.content,UNIX_TIMESTAMP( b.sent_time ) AS unixtime, b.sent_time,b.reported, b.received, c.avatar, c.first_name, c.last_name, c.is_login
                                     FROM message b
                                     LEFT JOIN user_message a ON a.message_id = b.message_id
                                     LEFT JOIN user c ON c.user_id = b.to_user
                                     WHERE a.user_id =$userid
                                     AND b.message_type_id =1
                                     ORDER BY b.sent_time DESC
                                    "); 
         return $result->fetchAll(PDO::FETCH_OBJ);
     }
     
     //This function get online message of the user
      public function fetchOnlineMessagesByUserId($userid){
        $result = $this->_db->query("SELECT b.message_id, a.user_id, b.to_user, b.content, UNIX_TIMESTAMP( b.sent_time ) AS unixtime ,b.sent_time, b.received, b.reported,c.avatar, c.first_name, c.last_name, c.is_login
                                     FROM user_message a
                                     LEFT JOIN message b ON a.message_id = b.message_id
                                     LEFT JOIN user c ON c.user_id = a.user_id
                                     WHERE (b.to_user =$userid
                                     OR a.user_id =$userid)
                                     AND b.message_type_id =2
                                     ORDER BY b.sent_time DESC
                                   "); 
         return $result->fetchAll(PDO::FETCH_OBJ);
     }
     
     //This function gets all the sent messages sent by a user id
     public function fetchOnlineSentMessagesByUserId($userid){
        $result = $this->_db->query("SELECT b.message_id, a.user_id, b.to_user, b.content, b.sent_time, b.received, b.reported, c.avatar, c.first_name, c.last_name, c.is_login
                                     FROM user_message a
                                     LEFT JOIN message b ON a.message_id = b.message_id
                                     LEFT JOIN user c ON c.user_id = a.user_id
                                     WHERE (b.to_user =$userid and b.received=0)
                                     AND b.message_type_id =2 
                                     ORDER BY b.message_id ASC;
                                   "); 
         return $result->fetchAll(PDO::FETCH_OBJ);
     }
    
    //This function gets all the inbox messages by message id
    public function getInboxMessageById($messageid){
        $result = $this->_db->query("SELECT b.message_id, a.user_id, b.to_user, b.content, UNIX_TIMESTAMP( b.sent_time ) AS unixtime,b.sent_time, b.received, b.reported, c.avatar, c.first_name, c.last_name, c.email, c.is_login
                                     FROM user_message a
                                     LEFT JOIN message b ON a.message_id = b.message_id
                                     LEFT JOIN user c ON c.user_id = a.user_id
                                     WHERE b.message_id =$messageid"); 
        return $result->fetch(PDO::FETCH_OBJ);
    }
    
    //This function gets all the sent message by message id
    public function getSentMessageById($messageid){
        $result = $this->_db->query("SELECT b.message_id, a.user_id, b.to_user, b.content, UNIX_TIMESTAMP( b.sent_time ) AS unixtime,b.sent_time, b.received, b.reported,c.avatar, c.first_name, c.last_name, c.email, c.is_login
                                     FROM message b
                                     LEFT JOIN user_message a ON a.message_id = b.message_id
                                     LEFT JOIN user c ON c.user_id = b.to_user
                                     WHERE b.message_id =$messageid"); 
        return $result->fetch(PDO::FETCH_OBJ);
    } 
    
}




?>
