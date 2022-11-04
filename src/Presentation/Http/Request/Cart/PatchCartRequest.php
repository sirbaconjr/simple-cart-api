<?php

namespace App\Presentation\Http\Request\Cart;

use App\Domain\Enum\CartStatus;
use App\Presentation\Http\Request\Request;

class PatchCartRequest extends Request
{
    public readonly CartStatus $status;

    protected function setup(array $body): void
    {
        $status = CartStatus::tryFrom($body['status'] ?? '');

        if (!$status || $status != CartStatus::Bought) {
            throw new \Exception('Invalid status');
        }

        $this->status = $status;
    }
}
