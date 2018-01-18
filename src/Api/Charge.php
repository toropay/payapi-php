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
     * @return Domain
     *
     * @throws InvalidResponseException
     */
    public function createNew(Domain $charge): Domain
    {
        return $this->doRequest('post', '/wallet/charge', $charge->getCreateData());
    }

    /**
     * @param string $chargeId
     *
     * @return Domain
     *
     * @throws InvalidResponseException
     */
    public function find(string $chargeId): Domain
    {
        return $this->doRequest('post', '/wallet/charge/' . $chargeId);
    }
}
