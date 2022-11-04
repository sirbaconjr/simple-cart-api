<?php

namespace App\Application;

use App\Domain\Exception\ProductNotFound;
use App\Domain\Model\Product;
use App\Domain\Repository\Product\GetProductRepository;
use Symfony\Component\Uid\UuidV4;

class GetProductAction
{
    public function __construct(
        private readonly GetProductRepository $getProductRepository
    )
    {
    }

    /**
     * @param UuidV4 $id
     * @return Product
     * @throws ProductNotFound
     */
    public function __invoke(UuidV4 $id): Product
    {
        return $this->getProductRepository->getProduct($id);
    }
}
