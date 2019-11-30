<?php

namespace App\Controller;

use App\Repository\CurrencyRepository;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CurrencyController
 *
 * @package App\Controller
 * @author  Aurimas Niekis <aurimas@niekis.lt>
 * @Route("/api")
 */
class CurrencyController extends AbstractController
{
    private CurrencyRepository $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * @Route("/list", name="api_list_currencies")
     */
    public function list()
    {
        $currencies = $this->currencyRepository->findAll();

        $response = [];
        foreach ($currencies as $currency) {
            $response[] = [
                'code' => $currency->getCode(),
                'name' => $currency->getName(),
            ];
        }

        return $this->json(
            [
                'success' => true,
                'data'    => $response,
            ]
        );
    }

    /**
     * @Route("/rates", name="api_rates_currencies")
     */
    public function rates()
    {
        $url    = 'https://gist.githubusercontent.com/aurimasniekis/437b0e4ee49eb54c9ce190534ea2ac36/raw/' .
            '824797624d5c516717cc65fef1de1ae937681ceb/currencies.json';
        $client = new Client();

        $response = $client->get($url);
        $json     = json_decode($response->getBody()->getContents(), true, JSON_THROW_ON_ERROR);

        return $this->json(
            [
                'success' => true,
                'data'    => $json['rates'],
            ]
        );
    }
}
