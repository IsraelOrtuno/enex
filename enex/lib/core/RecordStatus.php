<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LoginStatus
 *
 * @author Segun
 */
class RecordStatus {
    //put your code here
    
    protected $_model;
    
    private $log_event = null;
    
    public function __construct(){
        
        $this->_model = new recordModel();
    }
    
    
    public function  getRecordStatus($status){
        
        $result = $this->_model->getLogStatusID($status);
        if ($result)
        {
            return $result->log_event_id;	
        }
        return 0;
    }
}

?>
