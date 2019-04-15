<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Payone\ClientApi\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Service
 * @copyright  Copyright (c) 2018 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class RequestToInputAdapter
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var array
     */
    private $dataKeys = [];

    /**
     * @param InputInterface $input
     * @param array $dataKeys
     */
    public function __construct(
        InputInterface $input,
        array $dataKeys
    ) {
        $this->input = $input;
        $this->dataKeys = $dataKeys;
    }

    /**
     * @inheritdoc
     */
    public function convert(Request $request): InputInterface
    {
        $data = [];
        foreach ($this->dataKeys as $key) {
            $data[$key] = $request->get($key, null);
        }
        $this->input->setData($data);
        return $this->input;
    }
}
