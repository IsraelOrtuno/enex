<?php

class Object {

    /**
     * Base URL
     *
     * @var string
     */
    public $name = '';

    /**
     * Conversión Objeto-a-String.
     * Todas sus clases heredadas pueden sobreescribir este método a convenir.
     *
     * @return  string  Nombre de la clase
     */
    public function toString()
    {
        return get_class($this);
    }
}
?>
