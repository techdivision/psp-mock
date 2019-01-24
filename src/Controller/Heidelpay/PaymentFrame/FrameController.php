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
use Symfony\Component\HttpFoundation\Response;

class FrameController extends AbstractController
{

    public function __construct(

    ) {

    }

    /**
     * @return Response
     */
    public function list()
    {
        return $this->render('heidelpay/payment/frame.html.twig');
    }
}
