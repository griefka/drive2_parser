<?php

require_once '../../../vendor/autoload.php';
require '../../Helpers/simple_html_dom.php';
require '../../Helpers/DI.php';

use Helpers\Queues\Workers\LogbookWorker;
use Parsers\ParserDrive2;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$repository = $container->get('logbookRepository');
$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$worker = new LogbookWorker($connection, (new ParserDrive2()), 'logbook_queue', $repository);
$worker->startWorker();
