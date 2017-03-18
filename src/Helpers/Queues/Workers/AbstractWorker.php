<?php

namespace Helpers\Queues\Workers;


use Parsers\ParserDrive2;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Repositories\AbstractRepository;

abstract class AbstractWorker
{
    public function __construct(AMQPStreamConnection $connection, ParserDrive2 $parser, $queueName, AbstractRepository $repository)
    {
        $this->connection = $connection;
        $this->channel = $this->connection->channel();
        $this->parser = $parser;
        $this->queueName = $queueName;
        $this->channel->queue_declare($queueName, false, true, false, false);
        $this->repository = $repository;
    }

    public function saveImage($image, $directory){
        $array = explode('.', $image);
        $extension = end($array);
        file_put_contents($_SERVER['DOCUMENT_ROOT'].'/src/images/'.$directory.time().rand(0,10000).'.'.$extension, file_get_contents($image));
    }

    public function startWorker()
    {
        $callback = function ($msg) {
            $params = json_decode($msg->body, true);
            foreach ($params as $param) {
                $this->repository->createOrUpdate('url', $param);
            }
            echo "Ready \n";
            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        };

        $this->channel->basic_qos(null, 1, null);
        $this->channel->basic_consume($this->queueName, '', false, false, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }

        $this->channel->close();
        $this->connection->close();
    }
}