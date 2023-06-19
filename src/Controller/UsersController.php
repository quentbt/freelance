<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ModifUserType;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{

    public function __construct(
        private readonly UserRepository $repository,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/users', name: 'user.index')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $users = $paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page', 1), 10
        );

        return $this->render('pages/users/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/users/infoUsers/{id}', name: 'user.info', methods: ['GET'])]
    public function infoUser(int $id): Response
    {
        $user = $this->repository->find($id);
        $services = $user->getServices();

        return $this->render('pages/users/infoUsers.html.twig',[
            'services' => $services,
            'user' => $user
        ]);
    }

    // Fonction pour supprimer son commentaire //
    #[Route('/users/infoUsers/{id}/deleteComUser', name: 'commentaire_user_delete', methods: ['GET'])]
    public function deleteCom(CommentaireRepository $commentaireRepository, int $id): Response{

        $commentaire = $commentaireRepository->find($id);

        if(!$commentaire){

            $this->addFlash('fail','Le commentaire n\'a pas été trouvé');
            $this->redirectToRoute('user.info',[
                'id' => $this->getUser()->getId(),
            ]);
        }

        $this->em->remove($commentaire);
        $this->em->flush();
        $this->addFlash('deleted','Votre commentaire a bien été suprrimer');

        return $this->redirectToRoute('user.info',[
            'id' => $this->getUser()->getId(),
        ]);
    }

    #[Route('/users/modifierUser/{id}', name: 'user.modifier.profil', methods: ['GET','POST'])]
    public function modifUser(User $user, Request $request): Response{

        $form = $this->createForm(ModifUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user = $form->getData();
            $this->em->persist($user);
            $this->em->flush();

            $this->addFlash(
                'modifier', 'Enregistrement pris en compte'
            );

            return $this->redirectToRoute('user.info',[
                'id' => $user->getId(),
            ]);
        }

        return $this->render('pages/users/modifUser.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
