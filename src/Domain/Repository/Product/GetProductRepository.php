<?php

namespace App\Domain\Repository\Product;

use App\Domain\Exception\ProductNotFound;
use App\Domain\Model\Product;
use Symfony\Component\Uid\UuidV4;

interface GetProductRepository
{
    /**
     * @param UuidV4 $id
     * @return Product
     * @throws ProductNotFound
     */
    public function getProduct(UuidV4 $id): Product;
}
