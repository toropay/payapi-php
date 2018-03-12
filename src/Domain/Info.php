<?php

declare(strict_types=1);

namespace Toro\Pay\Domain;

use Toro\Pay\AbstractModel;

/**
 * @property string $currency
 * @property string $balance
 * @property string $income
 * @property string $locale
 * @property User $user
 */
class Info extends AbstractModel
{

}
