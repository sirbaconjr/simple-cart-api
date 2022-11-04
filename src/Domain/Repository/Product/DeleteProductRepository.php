<?php

namespace App\Domain\Repository\Product;

use App\Domain\Exception\ProductNotFound;
use Symfony\Component\Uid\UuidV4;

interface DeleteProductRepository
{
    /**
     * @param UuidV4 $id
     * @return void
     * @throws ProductNotFound
     */
    public function deleteProduct(UuidV4 $id): void;
}
