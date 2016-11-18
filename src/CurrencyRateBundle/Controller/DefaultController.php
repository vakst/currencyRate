<?php

namespace CurrencyRateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $currencyRateList = $this->getDoctrine()
            ->getRepository('CurrencyRateBundle:CurrencyRate')
            ->findAll();

        return $this->render('CurrencyRateBundle:Default:index.html.twig', array('currencyRateList' => $currencyRateList));
    }

    /**
     * @Route("/rate")
     */
    public function getCurrencyRateAction(Request $request)
    {
		$response = array('result' => 'failed');

		$pair = $request->get('pair');

		if (in_array($pair,array_keys($this->getParameter('import.import_service')))) {
			if ($currenceRate = $this->getDoctrine()
            ->getRepository('CurrencyRateBundle:CurrencyRate')
            ->findOneBy(array('pair' => $pair))
	        ) {
				$response['result'] = 'success';
				$response['rate'] = $currenceRate->getRate();
	        } else {
	        	$response['error'] = 'No data';
	        }
		} else {
			$response['error'] = 'That currency pair is not supported';
		}

		return new JsonResponse($response);
    }
}
