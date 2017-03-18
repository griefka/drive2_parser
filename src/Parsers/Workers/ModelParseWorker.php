<?php

require_once '../../../vendor/autoload.php';
require '../../Helpers/simple_html_dom.php';
require '../../Helpers/DI.php';

use Helpers\Queues\Workers\ModelWorker;
use Parsers\ParserDrive2;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$repository = $container->get('modelRepository');
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$worker = new ModelWorker($connection, (new ParserDrive2()), 'model_queue', $repository);
$worker->startWorker();