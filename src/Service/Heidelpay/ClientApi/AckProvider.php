<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay\ClientApi;


use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;
use TechDivision\PspMock\Service\Interfaces\PspEntityDataProviderInterface;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http=>//www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class AckProvider implements PspEntityDataProviderInterface
{
    /**
     * @param PspEntityInterface $order
     * @return void
     */
    public function get(PspEntityInterface $order)
    {
        /** @var Order $order */
        $order->setStatus('SUCCESS');
        $order->setStatusCode('00');
        $order->setResult('ACK');
        $order->setValidation('ACK');
        $order->setReturn("Request successfully processed in ''Merchant in Connector Test Mode''");
    }
}
