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
 * This class maps an array to the specific heidelpay format
 *
 * Example:
 * KEY                        | urlencoded part
 * FRONTEND.PAYMENT_FRAME_URL=https%3A%2F%2Ftest-heidelpay.hpcgw.net%2Fngw%2FpaymentFrame
 *
 * @copyright  Copyright (c) 2019 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Lukas Kiederle <l.kiederle@techdivision.com
 */
class ArrayToStringMapper
{
    const SEPERATOR = '=';
    const CONNECTOR = '&';

    /**
     * Format:
     *
     * KEY=VALUE&KEY=VALUE$KEY=VALUE....
     *
     * @param $data
     * @return string
     */
    public function map(array $data)
    {
        $result = '';

        foreach ($data as $key => $value) {

            if ($result === '') {
                $result = $key . self::SEPERATOR . urlencode($value);
            }
            $result = $result . self::CONNECTOR . $key . self::SEPERATOR . urlencode($value);
        }

        return $result;
    }
}
