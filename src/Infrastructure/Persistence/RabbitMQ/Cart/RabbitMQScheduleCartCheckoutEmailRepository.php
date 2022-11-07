<?php

namespace App\Infrastructure\Persistence\RabbitMQ\Cart;

use App\Domain\Model\Cart;
use App\Domain\Repository\Cart\ScheduleCartCheckoutEmailRepository;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQScheduleCartCheckoutEmailRepository implements ScheduleCartCheckoutEmailRepository
{
    public function __construct(
        private readonly AMQPChannel $channel
    ) {}

    public function schedule(Cart $cart): void
    {
        $this->declareQueue();

        $msg = new AMQPMessage($cart->id->jsonSerialize());

        $this->channel->basic_publish(
            msg: $msg,
            routing_key: Queue::CHECKOUT_EMAIL->value
        );

        $this->channel->close();
    }

    private function declareQueue()
    {
        $this->channel->queue_declare(
            Queue::CHECKOUT_EMAIL->value,
            false,
            false,
            false,
            false
        );
    }
}
