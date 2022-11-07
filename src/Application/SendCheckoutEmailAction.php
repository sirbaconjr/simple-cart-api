<?php

namespace App\Application;

use App\Domain\Model\Cart;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SendCheckoutEmailAction
{
    public function __construct(
        private readonly MailerInterface $mailer
    )
    {
    }

    public function __invoke(Cart $cart): void
    {
        if (!$cart->user) {
            throw new \InvalidArgumentException("The cart does not have a user");
        }

        $body = "Items: \n";

        foreach ($cart->items as $item) {
            $price = $item->product->price * $item->amount;
            $body .= "* {$item->amount} {$item->product->name} -> {$price}\n";
        }

        $body .= "Total: {$cart->total()}";

        $email = (new Email())
            ->from('cart@mailtrap.io')
            ->to($cart->user->email)
            ->subject("Your cart checkout information")
            ->text($body);

        $this->mailer->send($email);
    }
}
