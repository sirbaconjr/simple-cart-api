<?php

namespace App\Presentation\Console;

use App\Application\SendCheckoutEmailAction;
use App\Domain\Repository\Cart\GetCartRepository;
use App\Infrastructure\Persistence\RabbitMQ\Cart\Queue;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\UuidV4;

#[AsCommand(
    name: 'app:listener:checkout-email-schedules',
    description: 'Listen to schedules to send checkout email'
)]
class ListenToCheckoutEmailSchedules extends Command
{
    public function __construct(
        private readonly AMQPChannel $channel,
        private readonly SendCheckoutEmailAction $emailToUserAction,
        private readonly GetCartRepository $getCartRepository
    )
    {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $callback = function (AMQPMessage $msg) use ($io) {
            $io->section("Receiving message: " . $msg->getBody());

            try {
                $io->writeln("Retrieving cart");
                $stock = $this->getCartRepository->getCart(UuidV4::fromString($msg->getBody()));
            } catch(\Exception) {
                $msg->ack();
                $io->error("The provided cart does not exist or does not have a user");
                return;
            }

            $io->success("Sending email to user");

            ($this->emailToUserAction)(
                $stock
            );

            $msg->ack();
        };

        $io->title("Listening to queue: " . Queue::CHECKOUT_EMAIL->value);

        $this->channel->queue_declare(
            Queue::CHECKOUT_EMAIL->value,
            false,
            false,
            false,
            false
        );

        $this->channel->basic_consume(Queue::CHECKOUT_EMAIL->value, '', false, false, false, false, $callback);

        while ($this->channel->is_open()) {
            $this->channel->wait();
        }

        return Command::SUCCESS;
    }
}
