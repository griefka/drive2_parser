<?php

require_once '../../../vendor/autoload.php';
require '../../Helpers/simple_html_dom.php';
require '../../Helpers/DI.php';

use Helpers\Queues\Workers\GenerationWorker;
use Parsers\ParserDrive2;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$repository = $container->get('generationRepository');
$worker = new GenerationWorker($connection, (new ParserDrive2()), 'generation_queue', $repository);
$worker->startWorker();
