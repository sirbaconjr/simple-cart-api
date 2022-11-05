<?php

namespace Tests\Application;

use App\Application\GetAllProductsAction;
use App\Domain\Repository\Product\GetAllProductsRepository;
use PHPUnit\Framework\TestCase;

class GetAllProductsActionTest extends TestCase
{
    private GetAllProductsRepository $getAllProductsRepository;
    private GetAllProductsAction $getAllProductsAction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->getAllProductsRepository = self::createMock(GetAllProductsRepository::class);
        $this->getAllProductsAction = new GetAllProductsAction($this->getAllProductsRepository);
    }

    public function testItGetsAllProducts()
    {
        $expectedReturn = [1,2,3];

        $this->getAllProductsRepository
            ->expects(self::once())
            ->method('getAll')
            ->willReturn($expectedReturn);

        $result = ($this->getAllProductsAction)();

        self::assertEquals($expectedReturn, $result);
    }
}
