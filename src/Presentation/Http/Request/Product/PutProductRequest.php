<?php

namespace App\Presentation\Http\Request\Product;

use App\Presentation\Http\Exception\BadRequestException;
use Symfony\Component\Uid\UuidV4;

class PutProductRequest extends ProductRequest
{
    public readonly UuidV4 $id;

    /**
     * @param array $body
     * @return void
     * @throws BadRequestException
     */
    protected function setup(array $body): void
    {
        $this->id = $this->getId($body);
        parent::setup($body);
    }

    /**
     * @param array $body
     * @return UuidV4
     * @throws BadRequestException
     */
    private function getId(array $body): UuidV4
    {
        if (!array_key_exists('id', $body) || !UuidV4::isValid($body['id'])) {
            throw new BadRequestException("id", "ID is not a valid UUID4 string");
        }

        return UuidV4::fromString($body['id']);
    }
}
