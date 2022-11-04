<?php

namespace App\Application;

use App\Domain\Model\Product;
use App\Domain\Repository\Product\GetProductRepository;
use App\Domain\Repository\Product\UpdateProductRepository;
use Symfony\Component\Uid\UuidV4;

class UpdateProductAction
{
    public function __construct(
        private readonly GetProductRepository $getProductRepository,
        private readonly UpdateProductRepository $updateProductRepository
    )
    {
    }

    public function __invoke(UuidV4 $id, string $name, string $description, float $price): void
    {
        $product = $this->getProductRepository->getProduct($id);

        $updatedProduct = new Product($product->id, $name, $description, $price);

        $this->updateProductRepository->update($updatedProduct);
    }
}
