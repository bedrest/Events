<?php

$libPath = realpath(__DIR__ . '/../library');
$vendorPath = realpath(__DIR__ . '/../vendor');
$testPath = realpath(__DIR__);

$paths = array(
    $libPath,
    $vendorPath,
    $testPath,
    get_include_path()
);

set_include_path(implode(PATH_SEPARATOR, $paths));

require_once $vendorPath . '/autoload.php';
require_once $libPath . '/BedRest/Events/Annotations.php';

/**
 * Taken from http://zaemis.blogspot.co.uk/2012/05/writing-minimal-psr-0-autoloader.html
 * 
 * Using this instead of spl_autoload() as it is completely, utterly broken. It lowercases all class names, meaning
 * that case-sensitive file systems don't have a hope of actually loading the classes you require.
 * Even on case-insensitive file systems this is still a problem if you use the PHPUnit autoloader in combination with
 * spl_autoload(), as PHPUnit's autoloader will include the file once using the proper file name, but the include
 * performed by PHP_CodeCoverage will include the file again with a lowercase filename. This causes an error as it is
 * an attempt to redeclare an existing class.
 */
spl_autoload_register(function ($classname) {
    $classname = ltrim($classname, "\\");
    preg_match('/^(.+)?([^\\\\]+)$/U', $classname, $match);
    $classname = str_replace("\\", "/", $match[1])
        . str_replace(array("\\", "_"), "/", $match[2])
        . ".php";
    include_once $classname;
});

