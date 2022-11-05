<?php

namespace Tests\Application;

use App\Application\DeleteProductAction;
use App\Domain\Exception\ProductNotFound;
use App\Domain\Repository\Product\DeleteProductRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class DeleteProductActionTest extends TestCase
{
    private DeleteProductRepository $deleteProductRepository;
    private DeleteProductAction $deleteProductAction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->deleteProductRepository = self::createMock(DeleteProductRepository::class);
        $this->deleteProductAction = new DeleteProductAction($this->deleteProductRepository);
    }

    public function testItThrowProductNotFoundWhenProductDontExist()
    {
        $id = UuidV4::v4();

        $this->deleteProductRepository
            ->expects(self::once())
            ->method('deleteProduct')
            ->with($id)
            ->willThrowException(new ProductNotFound($id));

        self::expectException(ProductNotFound::class);

        ($this->deleteProductAction)($id);
    }

    public function testItDeletesAProduct()
    {
        $id = UuidV4::v4();

        $this->deleteProductRepository
            ->expects(self::once())
            ->method('deleteProduct')
            ->with($id);

        ($this->deleteProductAction)($id);
    }
}
