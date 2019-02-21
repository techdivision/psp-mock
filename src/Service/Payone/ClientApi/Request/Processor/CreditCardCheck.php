<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ClientApi\Request\Processor;

use TechDivision\PspMock\Entity\Creditcard\Whitelist;
use TechDivision\PspMock\Repository\Creditcard\WhitelistRepository;
use TechDivision\PspMock\Service\Payone\ClientApi\Request\InputInterface;
use TechDivision\PspMock\Service\Payone\ClientApi\Request\ProcessorInterface;
use TechDivision\PspMock\Service\Payone\ClientApi\Request\OutputInterface;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class CreditCardCheck implements ProcessorInterface
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct(
        OutputInterface $output
    ) {
        $this->output = $output;
    }

    /**
     * @param InputInterface $input
     * @return OutputInterface
     */
    public function execute(InputInterface $input): OutputInterface
    {
        if (preg_match('/^[123456]/', $input->getData('cardpan'))) {
            $this->output->setData($this->getValidData($input));
        } else {
            $this->output->setData($this->getInvalidData($input));
        }

        return $this->output;
    }

    /**
     * @param InputInterface $input
     * @return array
     */
    private function getInvalidData(InputInterface $input)
    {
        return [
            'callback' => $input->getData('callback_method'),
            'data' => [
                'status' => 'INVALID',
                'errorcode' => $input->getData('cardcvc2'),
                'errormessage' => 'Mock service error message (Mock service provide CVC as errorcode)',
                'customermessage' => 'This is a PSP mock service error message. CVC as errorcode.'
            ]
        ];
    }

    /**
     * @param InputInterface $input
     * @return array
     */
    private function getValidData(InputInterface $input)
    {
        return [
            'callback' => $input->getData('callback_method'),
            'data' => [
                'status' => 'VALID',
                'pseudocardpan' => '9410010000076' . rand(210000, 299999),
                'truncatedcardpan' => $this->getTrundcatedCardPan($input->getData('cardpan')),
                'cardtype' => $input->getData('catdtype'),
                'cardexpiredate' => $this->getCardExpireDate($input),
            ]
        ];
    }

    /**
     * @param string $pan
     * @return string
     */
    private function getTrundcatedCardPan($pan)
    {
        $result = substr($pan, 0, 6);
        $result .= 'XXXXXX';
        $result .= substr($pan, -4);
        return (string)$result;
    }

    /**
     * @param InputInterface $input
     * @return string
     */
    private function getCardExpireDate(InputInterface $input)
    {
        $result = substr($input->getData('cardexpireyear'), -2);
        $result .= str_pad($input->getData('cardexpiremonth'), 2, '0', STR_PAD_LEFT);
        return (string)$result;
    }
}
