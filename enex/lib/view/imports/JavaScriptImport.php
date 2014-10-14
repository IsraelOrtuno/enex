<?php

class JavaScriptImport extends Import {

    /**
     * HTML de importación de javascript
     *
     * @var array
     */
    protected $_tags = array(
        'import' => '<script type="text/javascript" src="%c"></script>'
    );

}

?>
