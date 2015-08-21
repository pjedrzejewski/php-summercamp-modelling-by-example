<?php

namespace App\Bundle\Controller;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends ContainerAware
{
    public function homepageAction(Request $request)
    {
        $parameters = array();

        $html = $this->container->get('twig')->render('default/homepage.twig', $parameters);

        return new Response($html);
    }
}
