<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Messagerie;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    public function __construct(
        private readonly MessageRepository $repository,
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/message', name: 'app_message')]
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    #[Route('/messagerie/NouveauMessage/{id}', name: 'nouveau_message', methods: ['GET','POST'])]
    public function nouveauMessagerie(Request $request, int $id, UserRepository $userRepository): Response{

        $user = $userRepository->find($id);

        $newMessage = new Message();
        $newMessagerie = new Messagerie();

        $newMessagerie->setUser($this->getUser());
        $newMessagerie->setUserReceveur($user);
        $this->em->persist($newMessagerie);

        $form = $this->createForm(MessageType::class, $newMessage);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $newMessage = $form->getData();
            $newMessage->setUserEnvoyeur($this->getUser());
            $newMessage->setUserReceveur($user);
            $newMessage->setMessagerie($newMessagerie);


            $this->em->persist($newMessage);
            $this->em->flush();


            return $this->redirectToRoute('conversation', [
                'id' => $newMessagerie->getId(),
            ]);
        }

        return $this->render('pages/message/nouveauMessage.html.twig',[
            'form' => $form->createView(),
        ]);
    }


    #[Route('message/supprimer/{id}', name: 'delete.message', methods: 'GET')]
    public function supprMessage(int $id): Response{

        $message = $this->repository->find($id);

        if(!$message){

            return $this->redirectToRoute('app_messagerie');
        }

        $this->em->remove($message);
        $this->em->flush();

        return $this->redirectToRoute('conversation',[
            'id' => $message->getMessagerie()->getId(),
        ]);
    }
}
