<?php

namespace Helpers\Queues\Tasks;


use Parsers\ParserDrive2;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Repositories\AbstractRepository;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @property AMQPStreamConnection connection
 * @property \PhpAmqpLib\Channel\AMQPChannel channel
 */
abstract class AbstractTask
{

    protected $connection;
    /**
     * @var AbstractRepository
     */
    protected $repository;
    /**
     * @var ParserDrive2
     */
    protected $parser;

    protected $channel;
    protected $queueName;

    protected $content = [];


    /**
     * AbstractTask constructor.
     * @param AMQPStreamConnection $connection
     * @param ParserDrive2 $parser
     * @param $queueName
     * @param ContainerBuilder $containerBuilder
     */
    public function __construct(AMQPStreamConnection $connection,
                                ParserDrive2 $parser,
                                $queueName,
                                ContainerBuilder $containerBuilder
    )
    {
        $this->connection = $connection;
        $this->channel = $this->connection->channel();
        $this->parser = $parser;
        $this->queueName = $queueName;
        $this->channel->queue_declare($queueName, false, true, false, false);
        $this->container = $containerBuilder;
    }

    /** prepare content to worker
     * @return mixed
     */
    abstract protected function prepareContent();

    /**
     * Move tasks to queue
     */
    public function startTask(){

        $this->prepareContent();
        foreach ($this->content as $page){
                echo "[x] Start...\n";
                $msg = new AMQPMessage(json_encode($page),
                    array('delivery_mode' => 2) # make message persistent
                );
                $this->channel->basic_publish($msg, '', $this->queueName);
                echo "[x] Ready...\n";
        }

        $this->channel->close();
        $this->connection->close();
    }




}