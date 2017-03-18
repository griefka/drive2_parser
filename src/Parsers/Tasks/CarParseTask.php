<?php

require_once '../../../vendor/autoload.php';
require '../../Helpers/simple_html_dom.php';
require '../../Helpers/DI.php';

use Helpers\Queues\Tasks\CarTasks;
use Parsers\ParserDrive2;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$task = new CarTasks($connection, (new ParserDrive2()), 'car_queue', $container);
$task->startTask();

