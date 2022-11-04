<?php

namespace App\Domain\Repository\Product;

use Symfony\Component\Uid\UuidV4;

interface DeleteProductRepository
{
    /**
     * @param UuidV4 $id
     * @return void
     */
    public function deleteProduct(UuidV4 $id): void;
}
