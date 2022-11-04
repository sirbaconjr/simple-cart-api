<?php

namespace App\Domain\Repository\Product;

use App\Domain\Model\Product;

interface GetAllProductsRepository
{
    /**
     * @return Product[]
     */
    public function getAll(): array;
}
