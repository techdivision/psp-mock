<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ClientApi\StaticFile;

use Symfony\Component\HttpFoundation\Response;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class OutputToResponseAdapter
{
    const HTML_URL_MASK = '######HTML-URL-MASK######';

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
        $body = $output->getContent();

        $body = $this->maskHtmlUrls($body);
        $body = $this->changeApiUrls($body);
        $body = $this->unmaskHtmlUrls($body);

        $this->response->setContent($body);

        $this->response->headers->set('Content-Type', 'application/javascript');
        $this->response->headers->set('Keep-Alive', 'timeout=2, max=1000');
        $this->response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $this->response->headers->set('X-Content-Type-Options', 'nosniff');
        $this->response->headers->set('X-XSS-Protection', '1; mode=block');
        return $this->response;
    }

    /**
     * @param string $body
     * @return string
     */
    private function maskHtmlUrls(string $body): string
    {
        return str_replace(
            'secure.pay1.de/client-api/js/v1/payone_iframe.html',
            OutputToResponseAdapter::HTML_URL_MASK,
            $body
        );
    }

    /**
     * @param string $body
     * @return string
     */
    private function changeApiUrls(string $body): string
    {
        return str_replace(
            'secure.pay1.de/client-api',
            'psp-mock.test/payone/client-api',
            $body
        );
    }

    /**
     * @param string $body
     * @return string
     */
    private function unmaskHtmlUrls(string $body): string
    {
        return str_replace(
            OutputToResponseAdapter::HTML_URL_MASK,
            'secure.pay1.de/client-api/js/v1/payone_iframe.html',
            $body
        );
    }
}