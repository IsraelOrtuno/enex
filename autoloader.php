<?php

/**
 *
 * @param string $className Class or Interface name automatically
 *                          passed to this function by the PHP Interpreter
 */
function autoLoader($className){
    //Directories added here must be relative to the script going to use this file
    $directories = array(
        '',
        'enex/',
        'enex/config/',
        'enex/lib/',
        'enex/lib/component/',
        'enex/lib/controller/',
        'enex/lib/controller/login/',
        'enex/lib/core/',
        'enex/lib/database/',
        'enex/lib/model/',
        'enex/lib/view/',
        'enex/lib/view/imports/',

        'app/config/',

        'app/controller/',
        'app/model/',
        'app/lib/'
    
    );

    foreach($directories as $directory){
        $path = $directory.$className.'.php';
        if(file_exists($path)){
            include_once $path;
            return;
        }
    }
}

spl_autoload_register('autoLoader');

?>