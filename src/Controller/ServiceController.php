<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Service;
use App\Form\EditServiceType;
use App\Form\NewCommentaireType;
use App\Form\NouveauServiceType;
use App\Repository\AchatRepository;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{

    public function __construct(
        private readonly ServiceRepository $repository,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/service', name: 'index.service')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $service = $paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page', 1), 10
        );

        return $this->render('pages/service/index.html.twig', [
            'service' => $service
        ]);
    }

    #[Route('/service/infoService/{id}', 'service.info', methods: ['POST','GET'])]
    public function infoService(int $id, Request $request): Response{

        $service = $this->repository->findOneBy(["id"=> $id]);
        $commentaire = $service->getCommentaires();

        $comment = new Commentaire();
        $form = $this->createForm(NewCommentaireType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $commentaire = $form->getData();
            $commentaire->setUser($this->getUser());
            $commentaire->setService($service);
            $this->em->persist($commentaire);
            $this->em->flush();

            return $this->redirectToRoute('service.info',[
                'id' => $service->getId()
            ]);
        }

        return $this->render("pages/service/infoService.html.twig",[
            'service' => $service,
            'commentaire' => $commentaire,
            'form' => $form->createView()
        ]);
    }

    #[Route('service/editerService/{id}', 'service.edit', methods: ['GET','POST'])]
    public function editService(Service $service, Request $request): Response{

        $form = $this->createForm(EditServiceType::class, $service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $service = $form->getData();
            $this->em->persist($service);
            $this->em->flush();

            $this->addFlash(
                'modified',
                'vôtre service a bien été modifié'
            );

            return $this->redirectToRoute('user.info',[
                'id' => $this->getUser()->getId(),
            ]);

        }

        return $this->render('pages/service/editService.html.twig',[
            'form' => $form->createView()
        ]);
    }

    // Fonction pour créer un nouveau service //
    #[Route('/service/NouveauService', name: 'service.nouveau', methods: ['GET','POST'])]
    public function newService(Request $request): Response{

        $service = new Service();
        $form = $this->createForm(NouveauServiceType::class, $service);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $service = $form->getData();
            $service->setUser($this->getUser());
            $service->setNom(ucfirst($service->getNom()));
            $this->em->persist($service);
            $this->em->flush();

            $this->addFlash('created', 'Votre service a bien été créé');

            return $this->redirectToRoute('user.info', [
                'id' => $this->getUser()->getId(),
            ]);
        }

        return $this->render('pages/service/NouveauService.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route("/service/delete/{id}", name: "service.delete", methods: ['POST', 'DELETE'])]
    public function deleteService(Service $service, Request $request): Response
    {

        if($this->isCsrfTokenValid('delete' .  $service->getId(), $request->get('_token')))
        {
            $this->em->remove($service);
            $this->em->flush();

            $this->addFlash('deleted', 'Le service à bien été supprimer');
        }

        return $this->redirectToRoute('user.info',[
            'id' => $service->getUser()->getId(),
        ]);
    }

}
