<?php

class Controller extends Object {

    /**
     * Controller layout
     *
     * @var string
     */
    public $layout = '';

    /**
     * Template to be used
     *
     * @var string
     */
    public $template = '';

    /**
     * Action
     *
     * @var string
     */
    private $_action;

    /**
     * View
     *
     * @var View
     */
    private $_view;

    /**
     * Variables to be used in the view
     *
     * @var array
     */
    private $_viewVars = array();

    /**
     * Controller data model
     *
     * @var Model
     */
    protected $_model = null;


    /**
     * JavaScript files to be used
     *
     * @var array
     */
    protected $_scripts = array();

    /**
     * CSS files to be used
     *
     * @var array
     */
    protected $_styles = array();

    /**
     * Controller name
     *
     * @var String
     */
    public $name = '';
    
    /**
     * Login
     * 
     * @var Login 
     */
    public $login;
    

    public function __construct($action = null)
    {
        $name               = str_replace('Controller', '', get_class($this));
        $this->name         = $name;

        $this->_action = $action;

        if ($name != 'login')                                   // Avoiding infinite loop when creating a login controller, it would create a login controller into it, and so on
            $this->login   = new loginController();

        $modelClass = $name.'Model';
        if (class_exists($modelClass))
        {
            $this->_model = new $modelClass();
            $this->set('data', $this->_model);
        }

        $this->_view = new View($this, $this->_model);
    }

    /**
     * Executes the method requested
     *
     * @return  void
     */
    public function execute()
    {
        $method = $this->_action;
        $this->$method();
    }

    /**
     * Returns the contect of the view already rendered and ready to be printed in the screen
     *
     * @return  string  HTML code
     */
    public function getOutput()
    {
        if (empty($this->template))
            $this->template = $this->_action;

        $this->layout   = App::layoutFile($this->layout);
        $this->template = App::templateFile($this->name, $this->template);
     

        $this->_scripts = array_merge($this->_scripts, Config::get('htmlimports', 'javascript'));
        $this->_styles  = array_merge($this->_styles, Config::get('htmlimports', 'css'));

        return $this->_view->getOutput();
    }

    /**
     * Creates a variable to be used in the view
     *
     * @param   mixed   $var1   Variable name or array of names
     * @param   mixed   $var2   Variable value or array of values
     *
     * @return  void
     */
    protected function set($var1, $var2 = null)
    {
        $data = array();

        if (is_array($var1))
        {
            if (is_array($var2))
                $data = array_combine($var1, $var2);
            else
                $data = $var1;
        }
        else
            $data = array($var1 => $var2);

        $this->_viewVars = array_merge($this->_viewVars, $data);
    }

    /**
     * Adds a JS file to the importation list
     *
     * @param   string  $source     JS file
     *
     * @return  void
     */
    protected function addScript($source)
    {
        array_push($this->_scripts, $source);
    }

    /**
     * Adds a CSS file to be imported
     *
     * @param   string  $source     CSS file
     *
     * @return  void
     */
    protected function addStyle($source)
    {
        array_push($this->_styles, $source);
    }

    /**
     * Executes an action of the own controller based on a new HTTP request.
     * (!) If a controller funcion is needed, it can be called directly by the
     * controller. This funcion will create a new HTTP request. All the data not
     * storaged will be lost after calling this.
     *
     * @param   string  $action     Controller action
     *
     * @return  void
     */
    protected function gotoAction($action)
    {
        $this->gotoController($this->name, $action);
    }

    /**
     * Execuets an action from another controller based o a new HTTP request.
     * (!) This will create a new HTTP request. All the data not storaged will
     * be lost after calling this.
     *
     * @param   string  $controller     Controller
     * @param   string  $action         Controller action
     *
     * @return  void
     */
    protected function gotoController($controller, $action)
    {
        header('location: '.WEBROOT.'index.php?c='.$controller.'&a='.$action);
        //header('location: '.WEBROOT.$controller.'/'.$action.'/');
    }

    /**
     * Will give 'true' if there are any POST header. It's an alternative to
     * isset($_POST)
     *
     * @return boolean
     */
    protected function isPostAction()
    {
        if ($_POST)
            return true;
        
        return false;
    }

    /**
     * Returns the POST variables. It can be used instead of $_POST.
     *
     * @return array
     */
    protected function getPostVars()
    {
        if ($this->isPostAction())
            return $_POST;
        
        return array();
    }

    /**
     * Returns the action the controller will execute
     *
     * @return string
     */
    public function getAction()
    {
        return $this->_action;
    }

    /**
     * Returns the variables created to be executed in the view
     *
     * @return array
     */
    public function getViewVars()
    {
        return $this->_viewVars;
    }

    /**
     * Returns the JS files that will be imported
     *
     * @return array
     */
    public function getScripts()
    {
        return $this->_scripts;
    }

    /**
     * Returns the CSS files that will be imported
     *
     * @return array
     */
    public function getStyles()
    {
        return $this->_styles;
    }
    
    /**
     * 
     */
    public function logOut()
    {
        $this->login->logout();
    }

}
?>
