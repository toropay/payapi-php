<?php

declare(strict_types=1);

namespace Toro\Pay\Domain;

use Toro\Pay\AbstractModel;

/**
 * @property User $user
 * @property int amount
 * @property string note
 * @property string returnUri
 * @property string|null authorizeUri
 * @property string state
 * @property string failureReason
 */
class Charge extends AbstractModel
{
    const STATE_CREATE = 'create';
    const STATE_PROCESSING = 'processing';
    const STATE_FINISHED = 'finished';
    const STATE_FAILED = 'failed';

    public function getCreateData(): array
    {
        return [
            'amount' => $this->amount,
            'note' => $this->note,
            'return_uri' => $this->returnUri,
        ];
    }
}
