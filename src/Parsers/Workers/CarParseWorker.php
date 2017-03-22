<?php

require_once '../../../vendor/autoload.php';
require '../../Helpers/simple_html_dom.php';
require '../../Helpers/DI.php';

use Helpers\Queues\Workers\CarWorker;
use Parsers\ParserDrive2;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$repository = $container->get('carRepository');
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$worker = new CarWorker($connection, (new ParserDrive2()), 'car_queue', $repository, $container);
$worker->startWorker();
