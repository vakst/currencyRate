<?php

namespace CurrencyRateBundle\Entity;

/**
 * CurrencyRate
 */
class CurrencyRate
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $rate;

    /**
     * @var string
     */
    private $pair;

    /**
     * @var \DateTime
     */
    private $updateDate = 'now()';

    public function __construct()
    {
        $this->updateDate = new \DateTime();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rate
     *
     * @param float $rate
     *
     * @return CurrencyRate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set pair
     *
     * @param string $pair
     *
     * @return CurrencyRate
     */
    public function setPair($pair)
    {
        $this->pair = $pair;

        return $this;
    }

    /**
     * Get pair
     *
     * @return string
     */
    public function getPair()
    {
        return $this->pair;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     *
     * @return CurrencyRate
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }
}

