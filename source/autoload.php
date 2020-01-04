<?php



require(__DIR__.'/mustache/src/Mustache/Autoloader.php');
$mustableAutoloader = new Mustache_Autoloader();
$mustableAutoloader->register();



spl_autoload_register(function ($calledClassName) {

    $normalizedClassName = preg_replace('`^\\\\`', '', $calledClassName);


    if(strpos($normalizedClassName, 'Phi\Template') === 0) {

        $relativeClassName = str_replace('Phi\Template', '', $normalizedClassName);
        $relativePath = str_replace('\\', '/', $relativeClassName);


        $definitionClass = __DIR__.'/class'.$relativePath.'.php';
        if(is_file($definitionClass)) {
            include($definitionClass);
        }
    }

});


