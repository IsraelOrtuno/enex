<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of recordController
 *
 * @author Segun
 */
class recordController extends Controller{
    //put your code here
    
    //create class instance and instantiate recordModel class
    public function __construct(){
        $this->_model = new recordModel();
    }
    
    public function addRecord($userID,$logEventID){
        
        try{
            $result = $this->_model->addRecord($userID, $logEventID);
        
            if($result<1)
                return "There was an error inserting Records in database";
            else
                return "";
            
        }catch(Exception $exception){
            echo $exception->getMessage();
        }
    }
}

?>
