<?php

class bulletinBoardModel extends Model
{
    /**
     * Gives back an array of topics.
     */
    public function getTopicList()
    {
        $sql = "SELECT * FROM topic
                INNER JOIN user ON user.user_id = topic.user_id
                INNER JOIN user_type ON user.user_type_id = user_type.user_type_id
                    ORDER BY creation_date DESC, topic_id DESC";
        
        $result = $this->_db->query($sql);
        
        if ($result->rowCount() > 0)
            return $result->fetchAll(PDO::FETCH_OBJ);
        else
            return array();
    }
    
    /**
     * Returns the data of the topic passed by argument (id)
     */
    public function getTopic($topicId)
    {
        $sql = "SELECT * FROM topic
                INNER JOIN user ON user.user_id = topic.user_id
                INNER JOIN user_type ON user.user_type_id = user_type.user_type_id
                    WHERE topic_id = $topicId";
        
        $result = $this->_db->query($sql);
        
        if ($result->rowCount() > 0)
            return $result->fetch(PDO::FETCH_OBJ);
        else
            return false;
    }
    
    /**
     * Returns a list of messages which belong to the topic passed by argument (id)
     */
    public function getTopicMessages($topicId)
    {
        $sql = "SELECT * FROM topic_message
                INNER JOIN user ON user.user_id = topic_message.user_id
                    WHERE topic_id = $topicId
                    ORDER BY creation_date DESC, topic_message_id DESC";
        
        $result = $this->_db->query($sql);
        
        if ($result->rowCount() > 0)
            return $result->fetchAll(PDO::FETCH_OBJ);
        else
            return array();
    }
    
    /**
     * Creates a new topic setting its title, message and user (id)
     */
    public function createTopic($title, $message, $user)
    {
        $date = date('Y-m-d H:i:s');
        list($title, $message) = array(addslashes($title), addslashes($message));       // Avoiding insert problems adding slashes to special characters like ' or " -> \' \"
                
        $sql = "INSERT INTO topic (title, message, user_id, creation_date)
                VALUES ('$title', '$message', '$user', '$date')";

        $this->_db->query($sql);
    }
    
    /**
     * Deletes a topic and its messagess
     */
    public function deleteTopic($topicId)
    {
        $sql = "DELETE FROM topic_message
                WHERE topic_id=$topicId";           // Deleting all messages in the topic
        $this->_db->query($sql);
        
        $sql = "DELETE FROM topic
                WHERE topic_id=$topicId";           // Deleting topic
        $this->_db->query($sql);
    }
    
    /**
     * Modifies the content of a topic (id)
     */
    public function updateTopic($topicId, $title, $message)
    {
        $date = date('Y-m-d H:i:s');
        list($title, $message) = array(addslashes($title), addslashes($message));       // Preventing ' and " problems

        $sql = "UPDATE topic SET
                title='$title',
                message='$message',
                modification_date='$date'
                    WHERE topic_id=$topicId";           
        $this->_db->query($sql);
    }
    
    /**
     * Adds a new message to a topic (id)
     */
    public function createMessage($topicId, $message, $user)
    {        
        $date = date('Y-m-d H:i:s');
        $message = addslashes($message);                                    // Preventing ' and " problems
        
        $sql = "INSERT INTO topic_message (topic_id, user_id, message, creation_date)
                VALUES ('$topicId', '$user', '$message', '$date')";
        
        $this->_db->query($sql);
    }
    
    /**
     * Returns the data of the message passed by argument (id)
     */
    public function getMessage($messageId)
    {
        $sql = "SELECT * FROM topic_message
                INNER JOIN user ON user.user_id = topic_message.user_id
                INNER JOIN user_type ON user.user_type_id = user_type.user_type_id
                    WHERE topic_message_id = $messageId";
        
        $result = $this->_db->query($sql);
        
        if ($result->rowCount() > 0)
            return $result->fetch(PDO::FETCH_OBJ);
        else
            return false;
    }
    
    /**
     * Deletes a message passed by argument (id)
     */
    public function deleteMessage($messageId)
    {
        $sql = "DELETE FROM topic_message
                WHERE topic_message_id=$messageId";           // Deleting message
        $this->_db->query($sql);
    }
    
    /**
     * Modifies a message
     */
    public function updateMessage($messageId, $message)
    {
        $date = date('Y-m-d H:i:s');
        $message = addslashes($message);                // Preventing ' and " problems

        $sql = "UPDATE topic_message SET
                message='$message',
                modification_date='$date'
                    WHERE topic_message_id=$messageId";           
        $this->_db->query($sql);
    }
}

?>
