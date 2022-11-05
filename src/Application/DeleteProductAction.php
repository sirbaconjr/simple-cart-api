<?php

namespace App\Application;

use App\Domain\Exception\ProductNotFound;
use App\Domain\Repository\Product\DeleteProductRepository;
use Symfony\Component\Uid\UuidV4;

class DeleteProductAction
{
    public function __construct(
        private readonly DeleteProductRepository $deleteProductRepository
    )
    {
    }

    /**
     * @param UuidV4 $id
     * @return void
     * @throws ProductNotFound
     */
    public function __invoke(UuidV4 $id)
    {
        $this->deleteProductRepository->deleteProduct($id);
    }
}
