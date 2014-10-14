<?php

class View extends Object
{
    /**
     * Layout the controller will use
     *
     * @var string
     */
    private $_layout;

    /**
     * Template to be used by the action called
     *
     * @var string
     */
    private $_template;

    /**
     * Variables to be loaded in the layout/template
     *
     * @var array
     */
    private $_viewVars;

    /**
     * View controller
     *
     * @var Controller
     */
    private $_controller;

    /**
     * Constructor
     *
     * @param Controller $controller View controller
     * @param Model      $model      Data model
     */
    public function __construct(Controller &$controller)
    {
        $this->_controller  = $controller;
    }

    /**
     * Renderiza el template y el layout, une el resultado de ambos (acoplando el template al layout) y lo retorna
     *
     * @return string   Layout y template renderizados (código HTML listo para imprimir en pantalla)
     */
    public function getOutput()
    {
        $this->_layout      = $this->_controller->layout;
        $this->_template    = $this->_controller->template;
        $this->_viewVars    = $this->_controller->getViewVars();

        $out = !empty($this->_template)?$this->renderTemplate():"View doesn't exist";
        $out = $this->renderLayout($out);

        return $out;
    }

    /**
     * Renderiza el template
     *
     * @return string   Template renderizado (código HTML)
     */
    private function renderTemplate() {
        $out = $this->render($this->_template, $this->_viewVars);
        return $out;
    }

    // NOTA: Probar si hace falta hacer el merge en renderlayout, ya que el resto de variables ya se agregaron con renderTemplate()
    /**
     * Renderiza el layout
     *
     * @param   string  $content    Contenido de un template (HTML)
     *
     * @return  string              Layout renderizado (codigo HTML)
     */
    private function renderLayout($content) {
        $vars = array_merge($this->_viewVars, array(
            'content'       =>  $content,
            'jsFiles'       =>  $this->getScripts(),
            'cssFiles'      =>  $this->getStyles(),
            'controller'    =>  $this->_controller->name
        ));

        if (!isset($vars['title']))
            $vars['title'] = "No title set";

        $out = $this->render($this->_layout, $vars);

        return $out;
    }

    /**
     * Renderiza el fichero que recibe y crea las variables del array $vars
     *
     * @param   string  $file   Archivo a renderizar
     * @param   array   $vars   Array de variables => valor
     * 
     * @return  string          Archivo renderizado (código HTML)
     */
    private function render($file, $vars)
    {
        extract($vars, EXTR_SKIP);
        ob_start();

        include($file);

        $out = ob_get_clean();

        return $out;
    }

    /**
     * Retorna el código HTML para las importaciones de los archivos JavaScript
     *
     * @return string   Código HTML
     */
    private function getScripts()
    {
        $import = new JavaScriptImport();
        $scripts = $this->_controller->getScripts();

        foreach ($scripts as $script)
            $import->addImport(App::JSFile($script));

        return $import->getOutput();
    }

    /**
     * Retorna el código HTML necesario para las importaciones de hojas de estilo
     *
     * @return string   Código HTML
     */
    private function getStyles()
    {
        $import = new StyleImport();
        $styles = $this->_controller->getStyles();

        foreach ($styles as $style)
            $import->addImport(App::CSSFile($style));

        return $import->getOutput();
    }
}

?>