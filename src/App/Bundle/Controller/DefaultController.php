<?php

namespace App\Bundle\Controller;

use App\Domain\BookCatalog\BookCatalogInterface;
use App\Domain\Isbn;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends ContainerAware
{
    public function homepageAction(Request $request)
    {
        $parameters = array();

        if ($request->query->has('query')) {
            $query = $request->query->get('query');

            $parameters['results'] = $this->getCatalog()->searchByIsbn(new Isbn($query));
        }

        $html = $this->container->get('twig')->render('default/homepage.twig', $parameters);

        return new Response($html);
    }

    /**
     * @return BookCatalogInterface
     */
    private function getCatalog()
    {
        return $this->container->get('app.catalog');
    }
}
