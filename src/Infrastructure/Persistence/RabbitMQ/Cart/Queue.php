<?php

namespace App\Infrastructure\Persistence\RabbitMQ\Cart;

enum Queue: string
{
    case CHECKOUT_EMAIL = 'checkout-email';
}
