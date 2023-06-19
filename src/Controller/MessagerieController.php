<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessagerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessagerieController extends AbstractController
{

    public function __construct(
        private readonly MessagerieRepository $repository,
        private readonly EntityManagerInterface $em,
    )
    {
    }
    #[Route('/messagerie', name: 'app_messagerie')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {

        $messagerie = $paginator->paginate(
            $this->repository->findAll(),
            $request->query->getInt('page',1),10
        );

        return $this->render('pages/messagerie/index.html.twig',[
            'messagerie' => $messagerie
        ]);
    }


    #[Route('/messagerie/conversation/{id}', name: 'conversation', methods: ['GET','POST'])]
    public function infoMessagerie(Request $request, int $id): Response{
//
        $messagerie = $this->repository->find($id);
        $userReceveur = $messagerie->getUserReceveur();
        $message = $messagerie->getMessages();

        $newMessage = new Message();

        $form = $this->createForm(MessageType::class, $newMessage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $newMessage = $form->getData();

            $newMessage->setUserEnvoyeur($this->getUser());
            $newMessage->setUserReceveur($userReceveur);
            $newMessage->setMessagerie($messagerie);
            $this->em->persist($newMessage);
            $this->em->flush();


            return $this->redirectToRoute('conversation',[
                'id' => $messagerie->getId()
            ]);
        }
//
        return $this->render('pages/message/Message.html.twig',[
            'messagerie' => $messagerie,
            'message' => $message,
            'form' => $form->createView(),
        ]);
    }


    #[Route('messagerie/deleteMessagerie/{id}',name: 'delete.messagerie', methods: 'GET')]
    public function deleteConv(int $id): Response{

        $messagerie = $this->repository->find($id);

        if(!$messagerie){

            $this->redirectToRoute('app_messagerie');
        }

        $this->em->remove($messagerie);
        $this->em->flush();

        return $this->redirectToRoute('app_messagerie');
    }
}
