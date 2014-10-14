<?php

class Router extends Object {

    /**
     * Controlador que se utilizará
     * 
     * @var string
     */
    private $_controller = '';

    /**
     * Acción del controlador a ejecutar
     *
     * @var string
     */
    private $_action = '';

    /**
     * Establece el controlador y acción que han de ejecutarse
     *
     * @param   string  $controller     Controlador que se utilizará
     * @param   string  $action         Acción que se ejecutará
     *
     * @return  void
     */
    public function setController($controller, $action = '')
    {
        if (empty($action))
            $this->_action = Config::get('routes', 'defaultAction');
        else
            $this->_action = $action;

        if (!empty($controller))
            $this->_controller = $this->getControllerName($controller);
        else
            $this->_controller = $this->getControllerName(Config::get('routes', 'defaultController'));
    }

    /**
     * Ejecuta el controlador
     *
     * @return Controller   El objeto controlador si todo ha ido bien, false si hay algún error
     */
    public function execute()
    {
        if (!class_exists($this->_controller))
        {
            throw new Exception("Controller not found $this->_controller");
        }
        
        if (!is_callable(array($this->_controller, $this->_action)))
            throw new Exception("Method not found in $this->_controller");

        $c = new $this->_controller($this->_action);

        $c->execute();

        return $c;
    }
    
    /**
     * 
     * @param type $controller
     * @return type 
     */
    private function getControllerName($controller)
    {
        return $controller.'Controller';
    }

}

?>