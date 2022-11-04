<?php

namespace App\Presentation\Http\Request;

use Slim\Psr7\Request as SlimRequest;

abstract class Request
{
    public function __construct(SlimRequest $request)
    {
        $body = $this->getBody($request);
        $this->setup($body);
    }

    protected function getBody(SlimRequest $request): array
    {
        $body = $request->getParsedBody();

        if (empty($body) || !is_array($body)) {
            throw new \Exception('Request body must be a valid non-empty json');
        }

        return $body;
    }

    protected abstract function setup(array $body): void;
}
