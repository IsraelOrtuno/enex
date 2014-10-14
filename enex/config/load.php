<?php
/**
 * Se inclueye el archivo de constantes de rutas
 */
require_once('paths.php');

/**
 * Se incluye el archivo de configuración de la base de datos
 */
require_once(CONFIG.'database.php');

/**
 * Se incluye el archivo de configuración de rutas
 */
require_once(CONFIG.'routes.php');

/**
 * Se incluye el archivo de configuración de importaciones para HTML
 */
require_once(CONFIG.'htmlimports.php');

/**
 * Fichero de constantes de la aplicación
 */
require_once(CONFIG.'constants.php');

/**
 * Se carga toda la configuración en la clase Config
 */
Config::load($config);

?>
