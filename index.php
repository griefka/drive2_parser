<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$loader = require __DIR__ . '/vendor/autoload.php';
//$loader->add('Helpers/DI', 'src/Helpers/DI.php');

require 'src/Helpers/simple_html_dom.php';
require 'src/Helpers/DI.php';

use Helpers\Queues\Tasks\BrandTasks;
use Helpers\Queues\Tasks\CarTasks;
use Helpers\Queues\Tasks\LogbookTasks;
use Helpers\Queues\Workers\BrandWorker;
use Helpers\Queues\Workers\CarWorker;
use Helpers\Queues\Workers\LogbookWorker;
use Parsers\ParserDrive2;
use PhpAmqpLib\Connection\AMQPStreamConnection;


$repository = $container->get('brandRepository');
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$shell = new BrandWorker($connection, (new ParserDrive2()), 'brand_queue', $repository);
$shell->startWorker();