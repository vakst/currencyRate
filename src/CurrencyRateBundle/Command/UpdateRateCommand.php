<?php
/*
 * This file is part of the CurrencyRateBundle package.
 *
 * (c) Vladimir Akst <contribute@akst.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CurrencyRateBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use CurrencyRateBundle\Exception\ImportException;
use CurrencyRateBundle\Entity\CurrencyRate;

/**
 * Update currency rates
 *
 * @author Vladimir Akst <contribute@akst.me>
 */
class UpdateRateCommand extends ContainerAwareCommand
{
    protected $io = null;

    /**
     * Configurate Command
     * @return void
     */
    protected function configure()
    {
        $this->setName('currencyRate:update')
        	->setDescription('Update currency rate from external source')
        	->addArgument('currencypaircode', InputArgument::REQUIRED, 'Currency pair code')
			->setHelp("Argument example: USD/RUR");
    ;
    }

    /**
     * Get currency rate and save in DB
     * @param InputInterface $input 
     * @param OutputInterface $output 
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        //Get data from one of the avaliable servers
        if ($currencyRate = $this->getRate($input->getArgument('currencypaircode'))) {
            $this->saveCurrencyRate($input->getArgument('currencypaircode'), $currencyRate);
        }        
    }

    /**
     * Update or insert new entity
     * @param string $pair
     * @param float $rate
     * @return void
     */
    protected function saveCurrencyRate($pair, $rate)
    {
        if ($currenceRate = $this->getContainer()->get('doctrine')
            ->getRepository('CurrencyRateBundle:CurrencyRate')
            ->findOneBy(array('pair' => $pair))
        ) {
           if ($rate <> $currenceRate->getRate()) {
                $currenceRate->setRate($rate);
                $em = $this->getContainer()->get('doctrine')->getManager();
                $em->persist($currenceRate);
                $em->flush();
           } 

        } else {
            //Insert new
            $currenceRate = new CurrencyRate();
            $currenceRate->setRate($rate);
            $currenceRate->setPair($pair);

            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->persist($currenceRate);
            $em->flush();
        }
    }

    /**
     * /Get currency rate from one of the avaliable servers
     * @param string $currencyPairCode 
     * @return float | NULL
     */
    protected function getRate($currencyPairCode)
    {
        if (array_key_exists($currencyPairCode, $this->getContainer()->getParameter('import.import_service'))) {
            //Get service list from config.yml
            $sourceList = $this->getContainer()->getParameter('import.import_service')[$currencyPairCode];
            //Trying to get data from all service.
            foreach ($sourceList as $serviceName => $serviceConfig) {
                $service = $this->getContainer()->get($serviceConfig['service']);

                try {
                    $currencyRate = $service->import($serviceConfig['url'], $serviceConfig);
                    return $currencyRate;
                } catch (ImportException $e) {
                    $this->io->comment($e->getMessage());
                }

            }
        } else {
            //Wrong command arguments
           $this->io->comment('Code doesn\'t supported.');
           $this->io->comment('Supported code list: '.implode(',', array_keys($this->getContainer()->getParameter('import.import_service'))));
        }

        return NULL;
    }
}