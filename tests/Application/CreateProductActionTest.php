<?php

namespace Tests\Application;

use App\Application\CreateProductAction;
use App\Domain\Model\Product;
use App\Domain\Repository\Product\CreateProductRepository;
use PHPUnit\Framework\TestCase;

class CreateProductActionTest extends TestCase
{
    private CreateProductRepository $createProductRepository;
    private CreateProductAction $createProductAction;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createProductRepository = self::createMock(CreateProductRepository::class);
        $this->createProductAction = new CreateProductAction($this->createProductRepository);
    }

    public function testItCreatesAProduct()
    {
        $name = 'name';
        $description = 'description';
        $price = 42.78;

        $validateProduct = function (Product $product) use ($name, $description, $price) {
            return $product->name === $name
                && $product->description === $description
                && $product->price === $price;
        };

        $this->createProductRepository
            ->expects(self::once())
            ->method('createProduct')
            ->with(self::callback($validateProduct));

        $product = ($this->createProductAction)($name, $description, $price);

        self::assertTrue($validateProduct($product));
    }
}
