<?php

$controller = isset($_GET['c'])?$_GET['c']:'';    // 
$action     = isset($_GET['a'])?$_GET['a']:'';    // 

$router = new Router();

$router->setController($controller, $action);

try
{
    $controller = $router->execute();
    print $controller->getOutput();
}
catch (Exception $e)
{
    echo $e->getMessage();
    exit();
}

?>