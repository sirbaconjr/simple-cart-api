<?php

namespace App\Application;

use App\Domain\Model\Product;
use App\Domain\Repository\Product\GetAllProductsRepository;

class GetAllProductsAction
{
    public function __construct(
        private readonly GetAllProductsRepository $getAllProductsRepository
    )
    {
    }

    /**
     * @return Product[]
     */
    public function __invoke(): array
    {
        return $this->getAllProductsRepository->getAll();
    }
}
