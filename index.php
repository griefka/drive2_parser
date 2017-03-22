<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$loader = require __DIR__ . '/vendor/autoload.php';

require 'src/Helpers/simple_html_dom.php';
require 'src/Helpers/DI.php';

$container->get('carsImagesRepository')->saveImages(4, [3,4,5]);