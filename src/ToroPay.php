<?php

declare(strict_types=1);

namespace Toro\Pay;

use Doctrine\Common\Inflector\Inflector;
use Toro\Pay\Api\Charge as ChargeApi;
use Toro\Pay\Api\User as UserApi;
use Toro\Pay\Hydrator\FacadeHydration;
use Toro\Pay\Hydrator\HydrationInterface;
use Toro\Pay\Provider\ResourceProvider;
use Toro\Pay\Provider\ResourceProviderInterface;

/**
 * @property ChargeApi charge
 * @property UserApi user
 */
final class ToroPay
{
    const VERSION = '1.0-dev';
    const SERVICE_NAME = 'toropay';
    const BASE_URL = 'https://toropay.co';
    const BASE_URL_SANDBOX = 'https://sandbox.toropay.co';

    /**
     * @var ResourceProviderInterface
     */
    private $resourceProvider;

    /**
     * @var HydrationInterface
     */
    private $hydration;

    /**
     * @var AbstractApi[]
     */
    private static $supports = [

    ];

    public function __construct(array $options, HydrationInterface $hydration = null)
    {
        $this->resourceProvider = new ResourceProvider($options);
        $this->hydration = $hydration;
    }

    /**
     * @param $apiClass
     *
     * @return AbstractApi
     */
    public function create($apiClass): AbstractApi
    {
        if (!in_array(AbstractApi::class, class_parents($apiClass))) {
            throw new \LogicException("The api class ($apiClass) should have sub-type of " . AbstractApi::class);
        }

        return new $apiClass($this->resourceProvider, $this->hydration);
    }

    /**
     * @param array $options
     */
    public static function setupFacade(array $options): void
    {
        $self = new self($options, new FacadeHydration());

        /**
         * @var AbstractFacade $domainClass
         * @var AbstractApi $apiClass
         */
        foreach (self::$supports as $domainClass => $apiClass) {
            if (!in_array(AbstractFacade::class, class_parents($domainClass))) {
                throw new \LogicException("The domain class ($domainClass) should have sub-type of " . AbstractFacade::class);
            }

            $domainClass::setApi($self->create($apiClass));
        }
    }

    /**
     * @param string $name
     *
     * @return AbstractApi
     */
    public function __get($name): AbstractApi
    {
        return $this->create(__NAMESPACE__ . "\\Api\\" . Inflector::classify($name));
    }
}
