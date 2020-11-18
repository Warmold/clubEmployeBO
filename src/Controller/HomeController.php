<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController.
 */
class HomeController extends AbstractController
{
    /**
     * @Route(name="home")
     *
     * @Template("index.html.twig")
     *
     * @return array
     */
    public function index(): array
    {
        return [];
    }
}
