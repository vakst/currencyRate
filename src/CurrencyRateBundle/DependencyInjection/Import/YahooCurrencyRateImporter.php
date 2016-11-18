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
use CurrencyRateBundle\Exception\ImportException;

/**
 *  Parsing data from Yahoo
 * 
 * @author Vladimir Akst <contribute@akst.me>
 */
class YahooCurrencyRateImporter implements CurrencyRateImporterInterface
{

    protected $loader = null;

    public function __construct(HttpLoader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * Load data
     *
     * @param string $url
     * @param string $paramList
     *
     * @return string
     */
    public function import($url, $paramList)
    {
        //Load data from internal source
        $jsonData = $this->loader->loadJSON($url);

        if (
            !empty($jsonData["query"])
            && !empty($jsonData["query"]["results"])
            && !empty($jsonData["query"]["results"]["rate"])
        ) {
            foreach ($jsonData["query"]["results"]["rate"] as $currencyData) {
                if (
                    !empty($currencyData['id'])
                    && !empty($currencyData['Rate'])
                    && $currencyData['id'] == $paramList['id']

                ) {
                    return (float)$currencyData['Rate'];
                }
            }
        }

        throw new ImportException("No data");
    }
}