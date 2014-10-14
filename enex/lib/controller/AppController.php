<?php

class AppController extends Controller
{
    protected $_requresLogin = false;           // This var sets if the user must be loged in the app to use the controller
    
    function __construct($action = null)
    {
        parent::__construct($action);
        
        if ($this->_requresLogin)
        {
            if (!$this->login->isLoged())
            {
                $this->template = 'empty';
                $this->layout   = 'empty';
                
                echo "<script>alert('This page is restricted to registered users. You will be redirected to the home page.'); location.href = 'index.php?c=home';</script>";
            }
            $temp_model = new vmanagementModel();
            $this->set('events', $temp_model->get_event_list(5));
        }
    }
}

?>