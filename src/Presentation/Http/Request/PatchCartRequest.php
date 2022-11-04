<?php

namespace App\Presentation\Http\Request;

use App\Domain\Enum\CartStatus;

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
