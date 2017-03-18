<?php

require_once '../../../vendor/autoload.php';
require '../../Helpers/simple_html_dom.php';
require '../../Helpers/DI.php';

use Helpers\Queues\Tasks\ModelTasks;
use Parsers\ParserDrive2;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$task = new ModelTasks($connection, (new ParserDrive2()), 'model_queue', $container);
$task->startTask();

