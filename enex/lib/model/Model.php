<?php

class Model {

    /**
     * Origen de datos
     *
     * @var DataSource
     */
    protected $_db;

    public function __construct()
    {
        $registry   = Registry::getInstance();
        $this->_db  = $registry->get('db');
    }

}
?>
