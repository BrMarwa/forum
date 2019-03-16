<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ForumController extends Controller
{
    /**
     * @Route("/accueil", name="accuiel")
     * @Template("@App/forum/index.html.twig")
     */
    public function indexAction(Request $request)
    {
        
    }
}
