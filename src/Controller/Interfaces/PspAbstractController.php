<?php
/**
 * Created by PhpStorm.
 * User: kiederlel
 * Date: 22.02.19
 * Time: 08:08
 */

namespace TechDivision\PspMock\Controller\Interfaces;


use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class PspAbstractController extends AbstractController
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * AbstractPspController constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
