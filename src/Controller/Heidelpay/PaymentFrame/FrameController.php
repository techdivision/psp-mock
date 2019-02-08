<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Heidelpay\PaymentFrame;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class FrameController extends AbstractController
{

    public function __construct()
    {
    }

    /**
     * Returns the Payment frame with a stateId in order to reference the payment order
     *
     * @param Request $request
     * @return Response
     */
    public function execute(Request $request)
    {
        return $this->render('heidelpay/payment/frame.html.twig', [
            'state' => $request->get('state')
        ]);

    }
}