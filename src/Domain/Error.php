<?php

/*
 * This file is part of the ToroPay package.
 *
 * (c) Ishmael Doss <nukboon@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Toro\Pay\Domain;

use Toro\Pay\AbstractModel;

/**
 * @property string message
 * @property string hint
 * @property string code
 * @property mixed data
 */
class Error extends AbstractModel
{
    /**
     * @var string
     */
    protected $idAttribute = 'code';

    /**
     * @var array
     */
    public $errors = [];

    public function __construct(array $store = [])
    {
        parent::__construct($store);

        if (400 === $this->code && !empty($store['errors'])) {
            $this->parseFormErrors($store['errors']['children']);

            if (empty($this->errors)) {
                $this->errors = $store['errors']['errors'] ?? [];
            }
        }
    }

    /**
     * Parse form errors!
     */
    private function parseFormErrors(array $childs)
    {
        foreach ($childs as $child => $error) {
            if (empty($error['errors'])) {
                continue;
            }

            // TODO: sub-child errors
            $this->errors = array_merge($this->errors, $error['errors']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function __get(string $name)
    {
        if ('message' === $name && !empty($this->store['hint'])) {
            return $this->store['message'] . " (" . $this->store['hint'] . ")";
        }

        return parent::__get($name);
    }
}
