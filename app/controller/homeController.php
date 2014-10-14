<?php

class homeController extends Controller
{    
    public $layout = 'default';
    
    public function index()
    {
       /* echo md5(uniqid(mt_rand(), true));
        $reg = Registry::getInstance();
        $login = $reg->get('login');
        
        $_SESSION['token'] = 'asdf';
        
        $user = $login->loginUser('wkikik@gmail.com', '123456789', 'asdf');
       
        if ($user)
            echo $user->type;
        else
            echo "User not found or wrong password";*/
        
        
        
        //$record = new RecordStatus();
        //echo $record->getRecordStatus("USER_EDITED");
    }
    
    public function aboutUs(){
        
    }
    
    public function terms_and_conditions(){
        
    }
    
    public function contacts(){
        
    }

    public function delete()
    {
        $this->template = 'delete';
    }
    
}

?>
