<?php

if (is_file(__DIR__ . 'vendor/cloudinary/cloudinary_php/autoload.php') && is_readable(__DIR__ . 'vendor/cloudinary/cloudinary_php/autoload.php')) {
    require_once __DIR__.'vendor/cloudinary/cloudinary_php/autoload.php';
} else {
    // Fallback to legacy autoloader
    require_once __DIR__.'vendor/cloudinary/cloudinary_php/autoload.php';
    require_once __DIR__.'vendor/cloudinary/cloudinary_php/src/Helpers.php';
}
