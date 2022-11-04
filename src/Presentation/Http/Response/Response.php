<?php

namespace App\Presentation\Http\Response;

use Slim\Psr7\Response as SlimResponse;

abstract class Response
{
    /**
     * @var array<string, string>
     */
    protected array $errors;

    public function build(SlimResponse $response): SlimResponse
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
