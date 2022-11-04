<?php

namespace App\Domain\Repository\Product;

use App\Domain\Model\Product;

interface UpdateProductRepository
{
    /**
     * @param Product $product
     * @return void
     */
    public function update(Product $product): void;
}
