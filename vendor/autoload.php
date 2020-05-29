<?php

// autoload.php @generated by Composer

require_once __DIR__ . '/composer/autoload_real.php';

return ComposerAutoloaderInit72c47c27716f1e3c541f211daf663bc1::getLoader();

define('CLOUDINARY_NAMESPACE', 'Cloudinary');

spl_autoload_register('cloudinary_autoloader');


/**
 * Callback function that searches for Cloudinary classes and autoloads them
 *
 * @param string $class_name Class to load

 * @return bool true if class is loaded, false otherwise
 */
function cloudinary_autoloader($class_name)
{
    // Ignore non-cloudinary classes
    if (strpos($class_name, CLOUDINARY_NAMESPACE) === false) {
        return false;
    }

    $classes_dir = realpath(__DIR__ . DIRECTORY_SEPARATOR . 'src') . DIRECTORY_SEPARATOR;
    $class_file = str_replace('_', DIRECTORY_SEPARATOR, $class_name) . '.php';
    $ns_prefix = CLOUDINARY_NAMESPACE . '\\';
    if (substr($class_file, 0, strlen($ns_prefix)) == $ns_prefix) {
        $class_file = substr($class_file, strlen($ns_prefix));
    }
    $class_file = str_replace('\\', DIRECTORY_SEPARATOR, $class_file);

    require_once $classes_dir . $class_file;

    return true;
}
