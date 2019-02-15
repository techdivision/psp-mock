<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use TechDivision\PspMock\Entity\Heidelpay\Order;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class QuoteConfirmer
{
    /**
     * @var DataProvider
     */
    private $dataProvider;

    /**
     * @var array
     */
    private $defaultOptions = [
        'verify' => false
    ];

    /**
     * QuoteConfirmer constructor.
     * @param DataProvider $dataProvider
     */
    public function __construct(DataProvider $dataProvider)
    {
        $this->dataProvider = $dataProvider;
    }

    /**
     * @param Order $order
     * @param $options
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute(Order $order, $options)
    {
        $client = new Client();

        if ($options === null) {
            $options = [];
        }

        /** @var ResponseInterface $response */
        $response = $client->request(
            'POST',
            $order->getResponseUrl(),
            [
                'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                'verify' => false,
                'form_params' => $this->buildQuoteUrl($order)
            ]
        );

        $order->setRedirectUrl((string)$response->getBody());

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getReasonPhrase());
        }
    }

    /**
     * @param Order $order
     * @return array
     */
    private function buildQuoteUrl(Order $order)
    {
        return $this->dataProvider->get($order);
    }
}
