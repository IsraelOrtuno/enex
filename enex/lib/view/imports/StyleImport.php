<?php

class StyleImport extends Import {

    /**
     * HTML de importación de estilos
     *
     * @var array
     */
    protected $_tags = array(
        'import' => '<link rel="stylesheet" type="text/css" href="%c" /> '
    );

}

?>
