<?php

namespace TechDivision\PspMock\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/index/index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }
}