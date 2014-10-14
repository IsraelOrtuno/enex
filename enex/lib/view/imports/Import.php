<?php

abstract class Import extends Object {

    /**
     * Array de archivos a importar
     *
     * @var array
     */
    protected $_output = array();

    /**
     * Array de etiquetas HTML
     *
     * @var array
     */
    protected $_tags;

    /**
     * Añade un nuevo archivo de importación
     *
     * @param   string  $source     Archivo de importación
     *
     * @return  void
     */
    public function addImport($source)
    {
        $result = str_replace('%c', $source, $this->_tags['import']);

        array_push($this->_output, $result);
    }

    /**
     * Retorna el código HTML para todos las importaciones del array
     *
     * @return string
     */
    public function getOutput()
    {
        $result = '';

        foreach ($this->_output as $out)
            $result .= $out."\n";

        return $result;
    }
}

?>
