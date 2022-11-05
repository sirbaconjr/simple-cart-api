<?php

namespace Tests\Application;

use App\Application\UpdateProductAction;
use App\Domain\Exception\ProductNotFound;
use App\Domain\Model\Product;
use App\Domain\Repository\Product\GetProductRepository;
use App\Domain\Repository\Product\UpdateProductRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class UpdateProductActionTest extends TestCase
{
    private GetProductRepository $getProductRepository;
    private UpdateProductRepository $updateProductRepository;
    private UpdateProductAction $updateProductAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->getProductRepository = self::createMock(GetProductRepository::class);
        $this->updateProductRepository = self::createMock(UpdateProductRepository::class);
        $this->updateProductAction = new UpdateProductAction(
            $this->getProductRepository,
            $this->updateProductRepository
        );
    }

    public function testItUpdatesAProduct()
    {
        $product = new Product(UuidV4::v4(), 'name', 'description', 42.78);
        $updatedName = 'new name';
        $updatedDescription = 'new description';
        $updatedPrice = 2;

        $this->getProductRepository
            ->expects($this->once())
            ->method('getProduct')
            ->with($product->id)
            ->willReturn($product);

        $this->updateProductRepository
            ->expects($this->once())
            ->method('update')
            ->with(self::callback(
                function (Product $product) use ($updatedName, $updatedDescription, $updatedPrice) {
                    return $product->name == $updatedName
                        && $product->description == $updatedDescription
                        && $product->price == $updatedPrice;
                }
            ));

        ($this->updateProductAction)($product->id, $updatedName, $updatedDescription, $updatedPrice);
    }

    public function testItThrowsProductNotFoundWhenProductDoesNotExist()
    {
        $id = UuidV4::v4();

        $this->getProductRepository
            ->expects($this->once())
            ->method('getProduct')
            ->with($id)
            ->willThrowException(new ProductNotFound($id));

        self::expectException(ProductNotFound::class);

        ($this->updateProductAction)($id, 'name', 'description', 42.78);
    }
}
