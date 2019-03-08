<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Gui;

use Symfony\Component\HttpFoundation\RedirectResponse;
use TechDivision\PspMock\Controller\Interfaces\PspAbstractController;
use TechDivision\PspMock\Controller\Interfaces\PspGuiIndexRedirectControllerInterface;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Controller
 * @copyright  Copyright (c) 2018 TechDivision GmbH (https://www.techdivision.com)
 * @link       https://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class IndexController extends PspAbstractController implements PspGuiIndexRedirectControllerInterface
{
    /**
     * @return RedirectResponse
     */
    public function index(): RedirectResponse
    {
        try {
            return $this->redirectToRoute('gui-order-list', ['type' => '']);
        } catch (\Exception $exception) {
            $this->logger->error($exception);
        }
    }
}
