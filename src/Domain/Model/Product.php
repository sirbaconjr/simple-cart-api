<?php

namespace App\Domain\Model;

use Symfony\Component\Uid\UuidV4;

class Product
{
    /**
     * @param UuidV4 $id
     * @param string $name
     * @param string $description
     * @param float $price
     */
    public function __construct(
        public readonly UuidV4 $id,
        public string $name,
        public string $description,
        public float $price
    ) {}
}
