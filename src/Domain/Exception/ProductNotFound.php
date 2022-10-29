<?php

namespace App\Domain\Exception;

use Symfony\Component\Uid\UuidV4;

class ProductNotFound extends \Exception
{
    public function __construct(UuidV4 $productId)
    {
        parent::__construct(
            message: "Product with ID '$productId' not found"
        );
    }
}
