<?php
/*
 * This file is part of the CurrencyRateBundle package.
 *
 * (c) Vladimir Akst <contribute@akst.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CurrencyRateBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use CurrencyRateBundle\DependencyInjection\CurrencyRateExtension;

/**
 * Update currency rates
 *
 * @author Vladimir Akst <contribute@akst.me>
 */
class CurrencyRateBundle extends Bundle
{
	public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new CurrencyRateExtension();
        }

        return $this->extension;
    }
}


