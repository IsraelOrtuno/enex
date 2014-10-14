<?php

class Registry extends Object {

    /**
     * Objetos del registro
     *
     * @var array
     */
    private $_objects;

    /**
     * Instancia única de la clase. Patrón singleton
     *
     * @var Registry
     */
    private static $_instance;

    /**
     * Constructor privado, evita que esta clase pueda ser instanciada. Para acceder a ella
     * hay que utilizar el método estático getInstance()
     */
    private function __construct()
    {
    }

    /**
     * Retorna la instancia de la clase
     *
     * @return Object
     */
    public static function getInstance()
    {
        if (!self::$_instance instanceof self)
            self::$_instance = new self;

        return self::$_instance;
    }

    /**
     * Retorna el objeto requerido
     *
     * @param   mixed   $key    Identificador del objeto
     *
     * @return  mixed           Objeto
     */
    public function get($key)
    {
        return $this->_objects[$key];
    }

    /**
     * Añade un objeto con un identificador
     *
     * @param   mixed   $key        Identificador del objeto
     * @param   mixed   $object     Objeto
     *
     * @return  void
     */
    public function add($key, $object)
    {
        $this->_objects[$key] = $object;
    }

}

?>