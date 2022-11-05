<?php

namespace App\Application;

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

        $product->name = $name;
        $product->description = $description;
        $product->price = $price;

        $this->updateProductRepository->update($product);
    }
}
