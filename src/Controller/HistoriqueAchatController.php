<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HistoriqueAchatController extends AbstractController
{public function __construct(
    private readonly UserRepository $userRepository,
//    private readonly EntityManagerInterface $em,
)
{
}
    #[Route('/historique/achat/{id}', name: 'app_historique_achat')]
    public function index(int $id): Response
    {
        $user = $this->userRepository->find($id);

        return $this->render('historique_achat/index.html.twig', [
            'achats' => $user->getAchats(),
        ]);
    }
}
