<?php

namespace App\Application;

use App\Domain\Model\Product;
use App\Domain\Repository\Product\CreateProductRepository;
use Symfony\Component\Uid\UuidV4;

class CreateProductAction
{
    public function __construct(
        private readonly CreateProductRepository $createProductRepository
    )
    {
    }

    public function __invoke(string $name, string $description, float $price): void
    {
        $product = new Product(UuidV4::v4(), $name, $description, $price);

        $this->createProductRepository->createProduct($product);
    }
}
