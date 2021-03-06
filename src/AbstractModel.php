<?php

/*
 * This file is part of the PhpMob package.
 *
 * (c) Ishmael Doss <nukboon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Toro\Pay;

use Doctrine\Common\Inflector\Inflector;
use Toro\Pay\Provider\ResourceProviderInterface;

/**
 * @property mixed id
 */
abstract class AbstractModel implements \JsonSerializable, \ArrayAccess
{
    /**
     * @var string
     */
    protected $idAttribute = 'id';

    /**
     * @var array
     */
    protected $store = [];

    /**
     * @param array $store
     */
    public function __construct(array $store = [])
    {
        $this->store = $store;
    }

    /**
     * @param array $store
     *
     * @return static
     */
    public static function make(array $store = []): self
    {
        return new static($store);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->store;
    }

    /**
     * @param array $data
     */
    public function updateStore(array $data = []): void
    {
        $this->store = $data;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->{$this->idAttribute};
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function __get(string $name)
    {
        if ('id' === $name) {
            return $this->store[$this->idAttribute];
        }

        $name = Inflector::tableize($property = $name);

        if (!array_key_exists($name, $this->store)) {
            return property_exists(get_called_class(), $property) ? $this->$property : null;
        }

        return $this->store[$name];
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        $this->store[Inflector::tableize($name)] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getResourceName()
    {
        return $this->store[ResourceProviderInterface::RESOURCE_NAME_KEY];
    }

    /**
     * @return null|string
     */
    public function getResourceUrl(): ?string
    {
        return $this->store['_links']['self']['href'] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return json_encode($this->store);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->store);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Only getter supported!');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Only getter supported!');
    }
}
