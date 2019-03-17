<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Service\Heidelpay\ClientApi;


/**
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class ArrayFormatter
{
    /**
     * @var ArrayToStringMapper
     */
    private $arrayToStringMapper;

    public function __construct(ArrayToStringMapper $arrayToStringMapper)
    {
        $this->arrayToStringMapper = $arrayToStringMapper;
    }

    /**
     * @param string $format
     * @param array $data
     * @return false|string
     * @throws \Exception
     */
    public function format(string $format, array $data)
    {
        switch ($format) {
            case 'json':
                return json_encode($data);
            case 'string':
                return $this->arrayToStringMapper->map($data);
            default:
                throw new \Exception("Could not apply $format on $data");
        }
    }
}
