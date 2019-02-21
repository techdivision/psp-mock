<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\PspMock\Controller\Gui;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use TechDivision\PspMock\Interfaces\Controller\PspGuiIndexControllerInterface;

/**
 * @category   TechDivision
 * @package    PspMock
 * @subpackage Controller
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com
 */
class IndexController extends AbstractController implements PspGuiIndexControllerInterface
{
    /**
     * @return RedirectResponse
     */
    public function index()
    {
        return $this->redirectToRoute('gui-order-list', ['type' => '']);
    }
}
