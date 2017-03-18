<?php

require_once '../../../vendor/autoload.php';
require '../../Helpers/simple_html_dom.php';
require '../../Helpers/DI.php';

use Helpers\Queues\Workers\BrandWorker;
use Parsers\ParserDrive2;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$repository = $container->get('brandRepository');
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$shell = new BrandWorker($connection, (new ParserDrive2()), 'brand_queue', $repository);
$shell->startWorker();
