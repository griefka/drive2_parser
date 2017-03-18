<?php
/**
 * Created by PhpStorm.
 * User: kai
 * Date: 13.03.17
 * Time: 14:58
 */

namespace Helpers;


use Parsers\ParserDrive2;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Repositories\AbstractRepository;
use Repositories\BrandRepository;

/**
 * @property AMQPStreamConnection connection
 * @property \PhpAmqpLib\Channel\AMQPChannel channel
 */
abstract class AbstractAmqpShell
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

    /**
     * AmqpShell constructor.
     * @param AMQPStreamConnection $connection
     * @param ParserDrive2 $parser
     * @param BrandRepository $repository
     */
    public function __construct(AMQPStreamConnection $connection, ParserDrive2 $parser, $queueName, AbstractRepository $repository)
    {
        $this->connection = $connection;
        $this->channel = $this->connection->channel();
        $this->parser = $parser;
        $this->queueName = $queueName;
        $this->channel->queue_declare($queueName, false, true, false, false);
        $this->repository = $repository;

    }

    abstract public function startTask();

    abstract public function startWorker();

//    public function getItems(){
//
//    }

//    public function startTask()
//    {
//       $allBrands = $this->parser->parseBrands();
//        $models = $this->parser->parseModelss();

//        foreach ($allBrands as $brand){

//            $modelPage = $this->parser->parseModelss($brand['url']);
//
//            foreach ($modelPage as $model){
//                $msg = new AMQPMessage(json_encode($model),
//                    array('delivery_mode' => 2) # make message persistent
//                );
//                $this->channel->basic_publish($msg, '', $this->queueName);
//
//            }

//        }

//            $msg = new AMQPMessage(json_encode($allBrands),
//                array('delivery_mode' => 2) # make message persistent
//            );
//            $this->channel->basic_publish($msg, '', $this->queueName);
//
//
//
//        $this->channel->close();
//        $this->connection->close();
//    }

//    public function startWorker()
//    {
//        $callback = function($msg){
//            $params = json_decode($msg->body, true);
//            foreach ($params as $param){
//            $this->repository->createOrUpdate('url', $param);
//            }
//            echo "Ready \n";
//            $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
//        };
//
//
//        $this->channel->basic_qos(null, 1, null);
//        $this->channel->basic_consume($this->queueName, '', false, false, false, false, $callback);
//
//        while(count($this->channel->callbacks)) {
//            $this->channel->wait();
//        }
//
//        $this->channel->close();
//        $this->connection->close();
//    }




}