<?php

namespace App\Presentation\Http\Response;

use Psr\Http\Message\ResponseInterface;

abstract class Response
{
    /**
     * @var array<string, string>
     */
    protected array $errors = [];

    public function build(ResponseInterface $response): ResponseInterface
    {
        $response->getBody()
            ->write(json_encode([
                'data' => $this->getData(),
                'errors' => $this->errors
            ]));

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus($this->getStatusCode());
    }

    protected abstract function getData(): array;

    protected abstract function getStatusCode(): int;
}
