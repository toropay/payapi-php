<?php

declare(strict_types=1);

namespace Toro\Pay\Api;

use Toro\Pay\AbstractApi;
use Toro\Pay\Exception\InvalidResponseException;
use Toro\Pay\Domain\Charge as Domain;

class Charge extends AbstractApi
{
    /**
     * @param Domain $charge
     *
     * @throws InvalidResponseException
     */
    public function createNew(Domain $charge)
    {
        return $charge->updateStore($this->doRequest('POST', '/wallet/charge', $charge->getCreateData())->toArray());
    }

    /**
     * @param string $chargeId
     *
     * @return Domain|null
     */
    public function find(string $chargeId): ?Domain
    {
        try {
            return $this->doRequest('GET', '/wallet/charge/' . $chargeId);
        } catch (InvalidResponseException $e) {
            return null;
        }
    }
}
