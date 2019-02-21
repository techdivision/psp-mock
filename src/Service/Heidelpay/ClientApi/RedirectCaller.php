<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay\ClientApi;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use TechDivision\PspMock\Entity\Heidelpay\Order;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class RedirectCaller
{
    /**
     * @var array
     */
    private $defaultOptions = [
        'verify' => false
    ];

    /**
     * @param Order $order
     * @param $options
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function execute(Order $order, $options)
    {
        $client = new Client(['defaults' => [
            'verify' => false
        ]]);

        if ($options === null) {
            $options = [];
        }

        /** @var ResponseInterface $response */
        $response = $client->request(
            'POST',
            $order->getRedirectUrl(),
            array_merge($this->defaultOptions, $options)
        );

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getReasonPhrase());
        }
    }

    /**
     * @param Order $order
     * @return string
     */
    private function buildQuoteUrl(Order $order)
    {
        return $this->dataProvider->get($order);
    }
}
