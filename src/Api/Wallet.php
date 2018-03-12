<?php

declare(strict_types=1);

namespace Toro\Pay\Api;

use Toro\Pay\AbstractApi;
use Toro\Pay\Exception\InvalidResponseException;
use Toro\Pay\Domain\Info as InfoDomain;

class Wallet extends AbstractApi
{
    /**
     * @return InfoDomain
     *
     * @throws InvalidResponseException
     */
    public function getInfo()
    {
        return $this->doRequest('GET', '/wallet/info');
    }
}
