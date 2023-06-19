<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Form\PaiementType;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaiementController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/paiement/{id}', name: 'app_paiement')]
    public function index(Request $request, int $id, ServiceRepository $serviceRepository): Response
    {
        $service = $serviceRepository->find($id);

        $form = $this->createForm(PaiementType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $infoCarte = $form->getData();

            $numCarte = $infoCarte['numCard'];
            $dateExp = $infoCarte['dateExpiration'];

            if($dateExp > date('Y-m-d')){

                $index = 0;

                $somme1 = 0;
                $somme2 = 0;

                while ($index <= strlen($numCarte)-1){

                    $numero = $numCarte[$index];

                    if($index%2 == 0) {

                        $num = strval((int)$numero * 2);
                        $num = (int)$num;

                        if ($num >= 10){

                            $num = ($num -10) + 1 ;
                        }

                        $somme1 += $num;
                    }else {

                        $somme2 += (int)$numero;
                    }
                    $index += 1;
                }
                $valid = ($somme1 + $somme2)%10;

                if($valid == 0){

                    $achat = new Achat();

                    $achat->setIdService($service);
                    $achat->setIdUser($this->getUser());
                    $this->em->persist($achat);
                    $this->em->flush();

                    $this->addFlash('PaiementAccepted','Votre Paiement a été valider');
                    return $this->redirectToRoute('home.index');
                }else{

                    $this->addFlash('cardRefused', 'Votre carte est invalide, renseignez-en une autre.');
                    return $this->redirectToRoute('app_paiement',[
                        'id' => $service->getId(),
                    ]);
                }

            }else{

                $this->addFlash('carteExpire','Vôtre carte est expiré, veuillez en sélectionner une autre');
            }
        }

        return $this->render('paiement/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
