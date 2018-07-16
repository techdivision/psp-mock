<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ServerApi;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use TechDivision\PspMock\Entity\Order;
use TechDivision\PspMock\Service\Payone\ServerApi\Callback\ActionFactory;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class CallbackExecutor
{
    /**
     * @var string
     */
    private $callbackUri = 'https://test-psp-mock.test/payone/transactionstatus';

    /**
     * @var ActionFactory
     */
    private $actionFactory;

    /**
     * CallbackExecutor constructor.
     * @param ActionFactory $actionFactory
     */
    public function __construct(ActionFactory $actionFactory)
    {
        $this->actionFactory = $actionFactory;
    }

    /**
     * @param Order $order
     * @param string $action
     * @throws \Exception
     */
    public function execute(Order $order, string $action): void
    {
        $client = new Client();

        $action = $this->actionFactory->create($action);
        $result = $action->apply($order);

        /** @var ResponseInterface $response */
        $response = $client->request(
            $result->getMethod(),
            $result->getUrl(),
            $result->getOptions()
        );

        $responseBody = (string)$response->getBody();
        if ($responseBody !== 'TSOK') {
            throw new \Exception($responseBody);
        }

        if ($response->getStatusCode() !== 200) {
            throw new \Exception($response->getReasonPhrase());
        }
    }
}