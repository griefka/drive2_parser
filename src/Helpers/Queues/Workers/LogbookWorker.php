<?php

namespace Helpers\Queues\Workers;


use Helpers\Images\SaveImage;

class LogbookWorker extends AbstractWorker
{
    use SaveImage;

    public function startWorker()
    {
        $callback = function ($msg) {
            $params = json_decode($msg->body, true);
            foreach ($params as $param) {
                foreach ($param['images'] as $image){
                    $this->saveImage($image, 'logbooks');
                }
                unset($param['images']);
                $logbook = $this->repository->createOrUpdate('url', $param);
                if (!empty($images)) {
                    foreach ($images as $image) {
                        $savedImage = $this->container->get('logbooksImagesRepository')->create(['url'=>$image]);
                        $ids[] = $savedImage['id'];
                    }
                    $this->container->get('carsImagesRepository')->saveImages($logbook['id'], $ids);
                }
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