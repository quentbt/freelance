<?php

namespace App\Controller;

use App\Entity\ServiceSearch;
use App\Form\ServiceSearchType;
use App\Repository\ServiceRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly PaginatorInterface $paginator,
        private readonly ServiceRepository $repository
    )
    {
    }

    #[Route('/', name: 'home.index')]
    public function index(Request $request): Response
    {
        $secteur = $this->repository->getAllSector();
        $i = 0;
        $listeSecteur[] = "";

        while ($i < count($secteur)-1){
            $listeSecteur[$i] = $secteur[$i]['secteur'];
            $i+=1;
        }

        $listeSecteur = array_unique($listeSecteur, SORT_STRING);

        $service = $this->paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page', 1), 10);

        return $this->render('index.html.twig', [
            'services' => $service,
            'secteurs' => $listeSecteur
        ]);
    }
}
