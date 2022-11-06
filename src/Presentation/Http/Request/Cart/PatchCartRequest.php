<?php

namespace App\Presentation\Http\Request\Cart;

use App\Domain\Enum\CartStatus;
use App\Presentation\Http\Exception\BadRequestException;
use App\Presentation\Http\Request\Request;

class PatchCartRequest extends Request
{
    public readonly CartStatus $status;

    /**
     * @param array $body
     * @return void
     * @throws BadRequestException
     */
    protected function setup(array $body): void
    {
        $status = CartStatus::tryFrom($body['status'] ?? '');

        if (!$status || $status != CartStatus::Bought) {
            throw new BadRequestException('status', 'Invalid status');
        }

        $this->status = $status;
    }
}
