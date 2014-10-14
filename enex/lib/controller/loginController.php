<?php

class loginController extends Controller
{ 
    public $layout = 'empty';
    public $template = 'empty';
    
    private $user = null;
    private $inactivityTimeout = 1800;    // User login timeout in seconds
    
    /*public function __construct($action = null)
    {
        parent::__construct($action);
        //$this->_model = new loginModel();
    }*/
    
    /**
     * Tries to log in a new user
     */
    public function loginUser($email = null, $pass = null)
    {
        if ($this->isPostAction())
            list($email, $pass) = array($_POST['email'], $_POST['password']);
        else
            list($email, $pass) = array($this->sanitize($email), $this->sanitize($pass));
        
        // Error messages to be displayed using AJAX in the default layout
        if (empty($email))                                              // Email required
            throw new Exception('Email required.');
        if (empty($pass))                                               // Password is required
            throw new Exception('Password required');
        if (!$this->checkEmail($email))                                 // Email validation
            throw new Exception('Invalid e-mail address.');
        
        $pass = md5($pass);                                             // MD5 password encoding before being compared
        
        $result = $this->_model->getUser($email, $pass);        
        
        if ($result)                                                    // If email and password match any table row
        {
            $this->user = $result;
            $_SESSION['last_activity'] = time();                        // Update last user's activity
            $_SESSION['user_id'] = $this->user->user_id;                // Set who is the user
            $this->_model->updateUserLogin($this->user->user_id,1);     // Set user to login
            
            return true;
        }
        else
        {
            throw new Exception('Invalid username or password.');       // Email wasn't found or password was wrong, throw exception
            return false;
        }
    }
    
    /**
     * Returns true if the actual client's got
     */
    public function isLoged()
    {
        if ($this->checkSession())
        {
            if (!isset($_SESSION['user_id']) || (empty($_SESSION['user_id'])))                  // User ID not found in session or session not set
                throw new Exception('Missing user id in SESSION.');
            
            $user = $this->_model->getUserById($_SESSION['user_id']);
            
            if (!$user)                                                                         // Session user id cannot be found in the DB
                throw new Exception('Recovering loged user details from database failed.');
            
            $this->user = $user;
 
            return true;
        }
        else
            return false;
    }
    
    public function getUserType()
    {
        return $this->user->type;
        
        return true;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    
    private function checkSession()
    {
        if ((!isset($_SESSION['last_activity']) || !isset($_SESSION['user_id'])) || ($_SESSION['last_activity'] + $this->inactivityTimeout) < time())   // User's inactivity is longer than allowed
        {
            //$this->logout();
            return false;
        }
        else
        {
            $_SESSION['last_activity'] = time();                // Update last user's activity
            return true;
        }
    }
    
    private function sanitize($data)
    {
        $data = trim($data);                        // Removing spaces at the beginning and end of the string
        $data = htmlspecialchars($data);            // Removing any HTML character
        $data = mysql_real_escape_string($data);    // Preventing SQL injection
        return $data;
    }
    
    private function checkEmail($email)
    {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            return false;
        
        return true;
    }
    
    public function logout()
    {
        $this->_model->updateUserLogin($_SESSION['user_id'],0); // This sets user to logout in the database
        unset($_SESSION['user_id']);
        unset($_SESSION['last_activity']);
        
        $this->gotoController(Config::get('routes', 'defaultController'), Config::get('routes', 'defaultAction'));
    }
}

?>