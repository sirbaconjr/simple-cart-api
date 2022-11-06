<?php

namespace App\Presentation\Http\Request;

use App\Presentation\Http\Exception\BadRequestException;
use Slim\Psr7\Request as SlimRequest;
use Symfony\Component\Uid\UuidV4;

abstract class Request
{
    /**
     * @param SlimRequest $request
     * @throws BadRequestException
     */
    public function __construct(SlimRequest $request)
    {
        $body = $this->getBody($request);
        $this->setup($body);
    }

    /**
     * @param SlimRequest $request
     * @return array
     * @throws BadRequestException
     */
    protected function getBody(SlimRequest $request): array
    {
        $body = $request->getParsedBody();

        if (empty($body) || !is_array($body)) {
            throw new BadRequestException('body', 'Request body must be a valid non-empty json');
        }

        return $body;
    }

    protected abstract function setup(array $body): void;
}
