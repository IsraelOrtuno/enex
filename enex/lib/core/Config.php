<?php

class Config {

    /**
     * Array de configuración
     *
     * @var array
     */
    private static $_data = array();

    /**
     * Carga un conjunto de propiedades en el array de información de configuración.
     *
     * @param   array   $data   Conjunto de propiedades.
     *
     * @return  void
     */
    public static function load($data)
    {
        if (is_array($data))
            self::$_data = array_merge($data);
    }

    /**
     * Retorna el valor de una propiedad de un grupo determinado de configuración.
     *
     * @param   string  $group      Grupo de configuración (ej: settings).
     * @param   string  $property   Propiedad del grupo de configuración.
     * 
     * @return  mixed               Valor de la propiedad.
     */
    public static function get($group, $property = '')
    {
        if (empty($property))
            return self::$_data[$group];
        
        return self::$_data[$group][$property];
    }

    /**
     * Escribe un valor a una propiedad de un grupo de configuración determinado.
     * 
     * @param   String  $group      Grupo de configuración.
     * @param   String  $property   Propiedad del grupo de configuración.
     *
     * @param   mixed   $value      Valor del grupo de configuración.
     */
    public static function set($group, $property, $value)
    {
        self::$_data[$group][$property] = $value;
    }
}
?>
