<?php

namespace Helpers\Queues\Workers;

use Helpers\Images\SaveImage;

class CarWorker extends AbstractWorker
{
    use SaveImage;

    public function startWorker()
    {
        $callback = function ($msg) {
            $params = json_decode($msg->body, true);
            foreach ($params as $param) {
                foreach ($param['images'] as $image) {
                    $images[] = $this->saveImage($image, 'cars');
                }
                unset($param['images']);
                $car = $this->repository->createOrUpdate('url', $param);
                if (!empty($images)) {
                    foreach ($images as $image) {
                        $savedImage = $this->container->get('carsImagesRepository')->create(['url'=>$image]);
                        $ids[] = $savedImage['id'];
                    }
                    $this->container->get('carsImagesRepository')->saveImages($car['id'], $ids);
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