<?php

class loginModel extends Model
{
    
    /**
     *
     * @param type $email
     * @param type $pass
     * @return type 
     */
    public function getUser($email, $pass)
    {
        $sql = "SELECT * FROM user
                JOIN user_type ON user.user_type_id = user_type.user_type_id
                WHERE email='$email'
                AND password='$pass'";
                
        $result = $this->_db->query($sql);
        
        if ($result->rowCount() > 0)
            return $result->fetch(PDO::FETCH_OBJ);
        else
            return false;
    }
    
    public function getUserById($id)
    {
        $sql = "SELECT * FROM user
                JOIN user_type ON user.user_type_id = user_type.user_type_id
                WHERE user_id='$id'";
        
        $result = $this->_db->query($sql);
        
        if ($result->rowCount() > 0)
            return $result->fetch(PDO::FETCH_OBJ);
        else
            return false;
    }
    
    
    
    public function getOtherUsers($user_id){
        $sql = "SELECT user_id, first_name , last_name , is_login ,email, avatar FROM user
        WHERE user_id != $user_id";
        $result = $this->_db->query($sql);
        
        return $result;
    }
    
    public function findUserByName ($user_id,$user_name){
        $sql = "SELECT user_id,first_name , last_name , is_login , avatar, email FROM user
        WHERE user_id != $user_id and CONCAT(trim(first_name),' ',trim(last_name)) LIKE '%$user_name%'";
        $result = $this->_db->query($sql);
        return $result;
    }
    
    public function findUserByDetail ($user_id,$detail){
        $sql = "SELECT user_id,first_name , last_name , email , avatar FROM user
        WHERE user_id != $user_id and (CONCAT(trim(first_name),' ',trim(last_name)) LIKE '%$detail%' OR email LIKE '%$detail%')";      
        $result = $this->_db->query($sql);
        return $result;
    }
    
    public function updateUserLogin($user_id,$login){
        $sql = "UPDATE user SET is_login=$login WHERE user_id = $user_id;";
        $result = $this->_db->exec($sql);
        return $result;
    }
}

?>
