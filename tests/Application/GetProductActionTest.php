<?php

namespace Tests\Application;

use App\Application\GetProductAction;
use App\Domain\Model\Product;
use App\Domain\Repository\Product\GetProductRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV4;

class GetProductActionTest extends TestCase
{
    private GetProductAction $getProductAction;
    private GetProductRepository $getProductRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getProductRepository = self::createMock(GetProductRepository::class);
        $this->getProductAction = new GetProductAction($this->getProductRepository);
    }

    public function testItGetsAProduct()
    {
        $product = new Product(UuidV4::v4(), 'name', 'description', 42.78);
        $this->getProductRepository
            ->expects($this->once())
            ->method('getProduct')
            ->with($product->id)
            ->willReturn($product);

        $result = ($this->getProductAction)($product->id);

        self::assertEquals($product, $result);
    }
}
