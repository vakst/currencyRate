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
 *  Parsing data from CBR
 * 
 * @author Vladimir Akst <contribute@akst.me>
 */
class CBRCurrencyRateImporter implements CurrencyRateImporterInterface
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
        $crawler = $this->loader->load($url);

        //Get node for char_code
        $node = $crawler
            ->filter(
                sprintf(
                    "Valute > CharCode:contains('%s')",
                    $paramList['char_code']
                )
            )
            ->first();

        if ($node->count() != 0) {
            $cValue = $node->parents()->filter('Value')->text();
            $cNominal = $node->parents()->filter('Nominal')->text();
            if (!empty($cValue) && !empty($cNominal)) {
                //calculate currency rate and return value
                return (float) str_replace(',', '.', $cValue)/$cNominal;
            } else {
                throw new ImportException("Wrong format of Valute tag");
                
            }
        }
    }
}