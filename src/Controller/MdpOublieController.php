<?php

namespace App\Controller;

use App\Form\MdpOublieType;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Sodium\add;

class MdpOublieController extends AbstractController
{
    #[Route('/mdp/oublie', name: 'app_mdp_oublie')]
    public function index(Request $request, UserRepository $repository): Response
    {
        $form = $this->createForm(MdpOublieType::class);
        $form->handleRequest($request);

        $verif = false;

        if($form->isSubmitted() && $form->isValid()){

            $mail = $form->getData();

            $index = 0;

            while($index <= count($repository->findAll())-1){

                $serviceMail = $repository->find($index);

                if($mail['email'] == $serviceMail){

                    $verif = true;
                }
                $index+=1;
            }

            if ($verif){

                $this->addFlash('success','Un email à été envoyer');
            }else{

                $this->addFlash('fail','Un email à été envoyer');
            }
            return $this->redirectToRoute('app_login');
        }

        return $this->render('mdp_oublie/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }
}
