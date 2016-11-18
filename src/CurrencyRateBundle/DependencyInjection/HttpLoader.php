<?php
/*
 * This file is part of the CurrencyRateBundle package.
 *
 * (c) Vladimir Akst <contribute@akst.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CurrencyRateBundle\DependencyInjection;

use CurrencyRateBundle\Exception\ImportException;
use Goutte\Client;

/**
 *  Load data
 * 
 * @author Vladimir Akst <contribute@akst.me>
 */
class HttpLoader
{

    protected $client = null;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieve data
     *
     * @param string $url 
     *
     * @return string
     */
    public function load($url)
    {

        //Last parameter "$changeHistory" must be false due to memory leaks
        $crawler = $this->client->request('GET', $url, array(), array(), array(), null, false);
        
        if($this->client->getResponse()->getStatus() <> 200) {
            throw new ImportException("Http request error");
        }
        
        return $crawler;
    }

    /**
     * Retrieve data
     *
     * @param string $url 
     *
     * @return string
     */
    public function loadJSON($url)
    {
        $this->load($url);
        return json_decode($this->client->getResponse()->getContent(), true);
    }
}