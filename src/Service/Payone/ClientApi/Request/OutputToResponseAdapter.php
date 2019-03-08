<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ClientApi\Request;

use Symfony\Component\HttpFoundation\Response;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class OutputToResponseAdapter
{
    /**
     * @var Response
     */
    private $response;

    /**
     */
    public function __construct()
    {
        $this->response = new Response();
    }

    /**
     * @inheritdoc
     */
    public function convert(OutputInterface $output): Response
    {
        $this->response->setContent(sprintf(
            '%s(%s)',
            $output->getData('callback'),
            json_encode($output->getData('data'))
        ));
        $this->response->headers->set('Content-Type', 'application/javascript');
        $this->response->headers->set('Keep-Alive', 'timeout=2, max=1000');
        $this->response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $this->response->headers->set('X-Content-Type-Options', 'nosniff');
        $this->response->headers->set('X-XSS-Protection', '1; mode=block');
        return $this->response;
    }
}
