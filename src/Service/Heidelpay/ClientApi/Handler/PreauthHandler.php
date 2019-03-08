<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay\ClientApi\Handler;

use Symfony\Component\HttpFoundation\Request;
use TechDivision\PspMock\Entity\Address;
use TechDivision\PspMock\Entity\Customer;
use TechDivision\PspMock\Entity\Heidelpay\Order;
use TechDivision\PspMock\Entity\Interfaces\PspEntityInterface;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class PreauthHandler extends AbstractHandler
{
    /**
     * @param Request $request
     * @return PspEntityInterface
     * @throws \Exception
     */
    public function handle(Request $request): PspEntityInterface
    {
        /** @var Order $order */
        $order = new Order();
        $address = new Address();
        $customer = new Customer();

        $this->requestMapper->map($request, $order, $address, $customer);

        $this->removeDuplicatedEntries($order->getTransactionId());

        // If flag is set return a 'NOK' Message
        ($this->failOnPreauth === '0')
            ? $this->ackProvider->get($order)
            : $this->nokProvider->get($order);

        $this->entitySaver->save([$address, $order, $customer]);

        return $order;
    }

    /**
     * This is necessary because the order is being created every time before
     * the account holder is set
     * @param string $transactionId
     */
    private function removeDuplicatedEntries(string $transactionId)
    {
        $order = $this->orderRepository->findOneBy(array('transactionId' => $transactionId));
        if ($order !== null) {
            $this->objectManager->remove($order);
            $this->objectManager->flush();
        }
    }
}
