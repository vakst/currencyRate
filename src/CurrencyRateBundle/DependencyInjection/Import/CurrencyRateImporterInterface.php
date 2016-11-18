<?php
/*
 * This file is part of the CurrencyRateBundle package.
 *
 * (c) Vladimir Akst <contribute@akst.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CurrencyRateBundle\DependencyInjection\Import;
use CurrencyRateBundle\DependencyInjection\HttpLoader;

/**
 *  Interface for parsers currency rates from html
 * 
 * @author Vladimir Akst <contribute@akst.me>
 */
interface CurrencyRateImporterInterface
{
    public function __construct(HttpLoader $loader);

    /**
     * Load and parse currency rate
     *
     * @param string $url
     * @param string $paramList
     *
     * @return string
     */
    public function import($url, $paramList);
}