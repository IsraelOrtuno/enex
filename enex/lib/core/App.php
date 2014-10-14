<?php

class App {

    /**
     * Retorna la ruta de un template
     *
     * @param   string  $name       Nombre del controlador
     * @param   string  $action     Acción del controlador
     * 
     * @return  string              Ruta del template
     */
    public static function templateFile($name, $action)
    {
        if ($action == 'empty')
            $path = TEMPLATES.'/empty.etp';
        else
            $path = TEMPLATES.$name.'/'.$action.'.etp';

        if (file_exists($path))
            return $path;

        return '';
    }

    /**
     * Retorna la ruta de un layout
     *
     * @param   string  $layout     Nombre del layout
     *
     * @return  string              Ruta del layout
     */
    public static function layoutFile($layout)
    {
        $path = LAYOUTS.$layout.'.etp';

        if (file_exists($path))
            return $path;

        return LAYOUTS.'default.etp';
    }

    /**
     * Retorna la ruta de un fichero JavaScript
     *
     * @param   string  $source     Nombre del fichero
     *
     * @return  string              Ruta del fichero
     */
    public static function JSFile($source)
    {
        if (strpos($source, 'http://') === false)
            return JS.$source;
        return $source;
    }

    /**
     * Retorna la ruta de una hoja de estilo
     *
     * @param   string  $source     Nombre de la hoja de estilo
     *
     * @return  string              Ruta de la hoja de estilo
     */
    public static function CSSFile($source)
    {
        return CSS.$source;
    }

}

?>
