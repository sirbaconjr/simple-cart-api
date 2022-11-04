<?php

namespace App\Domain\Repository\Product;

use App\Domain\Model\Product;

interface CreateProductRepository
{
    /**
     * @param Product $product
     * @return void
     */
    public function createProduct(Product $product): void;
}
