<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{

    public function __construct(
        private readonly EntityManagerInterface $em,
    )
    {
    }

    #[Route('/search', name: 'app_search', methods: 'POST')]
    public function index(Request $request, ServiceRepository $serviceRepository, UserRepository $userRepository): Response{

        $submittedToken = $request->get('app_search');

        $nouveauService[] = "";
        $nouveauUser[] = "";

        if($this->isCsrfTokenValid('app_search', $submittedToken)) {

            $nom = $request->get('search');
            $service = $serviceRepository->findOneBy(['nom' => $nom]);

            if($nom == "" || $nom == null){
                return $this->redirectToRoute('home.index');
            }else {

                // Si on a trouvé un service avec le nom complet
                if ($service != null) {

                    return $this->redirectToRoute('service.info', [
                        'id' => $service->getId()
                    ]);

                }else {

                    $user = $userRepository->findOneBy(['name' => $nom]);

                    // Si on a trouvé l'user avec son nom complet
                    if ($user != null) {

                        return $this->redirectToRoute('user.info', [
                            'id' => $user->getId(),
                        ]);

                    }else {

                        $nomServicePropose = $serviceRepository->getAllName();
                        $nomUserPropose = $userRepository->getAllName();

                        $limite = 0;
                        $i = 0;

                        $verifService = false;
                        $verifUser = false;


                        // Boucle pour chercher les services
                        while ($limite <= count($nomServicePropose)-1){

                            $nbrMot = 0;
                            $mot = $nomServicePropose[$limite]['nom'];
                            $motTrouve = explode(' ', $mot);

                            while ($nbrMot <= count($motTrouve)-1){

                                $motFinal = $motTrouve[$nbrMot];

                                if($motFinal == $nom){

                                    $nouveauService[$i] = $serviceRepository->findOneBy(['nom' => $mot]);
                                    $i +=1;
                                    $verifService = true;
                                }
                                $nbrMot += 1;
                            }
                            $limite += 1;
                        }

                        $limite = 0;


                        // Boucle pour chercher les utilisateurs
                        while ($limite <= count($nomUserPropose)-1){

                            $nbrMot = 0;
                            $mot = $nomUserPropose[$limite]['name'];
                            $motTrouve = explode(' ', $mot);

                            while ($nbrMot < count($motTrouve)-1){

                                $motFinal = $motTrouve[$nbrMot];

                                if ($motFinal == $nom){

                                    $nouveauUser[$i] = $userRepository->findOneBy(['name' => $mot]);
                                    $i += 1;
                                    $verifUser = true;
                                }

                                $nbrMot += 1;
                            }
                            $limite += 1;
                        }

                        if ($verifUser || $verifService){

                            return $this->render('search/infoTrouve.html.twig',[
                                'noms' => $nom,
                                'serviceFound' => $nouveauService,
                                'userFound' => $nouveauUser,

                                'verifUser' => $verifUser,
                                'verifService' => $verifService,

                            ]);
                        } else {

                            $this->addFlash('fail', 'Aucun résultat pour votre recherche ne correspond à'.$nom);
                            return $this->redirectToRoute('home.index');
                        }
                    }
                }
            }
        }
        return $this->redirectToRoute('home.index');
    }

    #[Route('/filter', name: 'filter', methods: ['POST','GET'])]
    public function filter(Request $request, ServiceRepository $serviceRepository, PaginatorInterface $paginator): Response{

        $submittedToken = $request->get('filter');

        $secteurfil = false;
        $datefil = false;

        $secteurFilter[] = "";
        $filtreList[] = "";

        if($this->isCsrfTokenValid('filter', $submittedToken)) {

            $filtreToutSecteur = $serviceRepository->getAllSector();
            $tab = 0;
            $secteurFilter[] = "";

            while ($tab < count($filtreToutSecteur)) {

                $secteurFilter[$tab] = $filtreToutSecteur[$tab]["secteur"];
                $tab += 1;
            }

            $secteurFilter = array_unique($secteurFilter, SORT_STRING); // Donne une liste ou chaque secteur est unique (les doublons sont supprimé)
            $secteur = $request->get('secteur');
            $prix = $request->get('prix');
            $date = $request->get('date');

            if ($date == null && $secteur == null && $prix == null){

                return $this->redirectToRoute('home.index');
            }

            $reducMois = false;

            if($date != null){
                $datefil = true;
            }
            if($date == 1){
                $date = date_timestamp_get(date_modify(new \DateTime, '-1 day'));
            } elseif ($date == 2){
                $date = date_timestamp_get(date_modify(new \DateTime, '-1 week'));
            } elseif ($date == 3){
                $date = date_timestamp_get(date_modify(new \DateTime, '-2 month'));
                $reducMois = true;
            }elseif ($date == 4){
                $date = date_timestamp_get(date_modify(new \DateTime, '-1 year'));
            }

            if ($secteur != null){

                $secteurfil = true;
            }

            $prixMin = 0;
            $prixMax = 99999999;

            if ($prix != null){

                $filtrePrixLimite = explode('-',$prix);
                $prixMin = (int)$filtrePrixLimite[0];
                $prixMax = (int)$filtrePrixLimite[1];
            }

            $tour = 0;
            $index = 0;
            $serviceTout = $serviceRepository->findAll();

            while ($tour < count($serviceTout)) {

                $service = $serviceRepository->findOneBy(['id' => $serviceTout[$tour]]);

                $servicePrix = $service->getPrix();
                $serviceSecteur = $service->getSecteur();
                $serviceDate = date_timestamp_get($service->getCreatedAt());

//                dd(date("j/m/Y", $date), date('j/m/Y',$serviceDate), $secteurfil, $datefil, $date, $serviceDate,$service);

                if ($prixMin < $servicePrix && $servicePrix <= $prixMax) {

                    if ($datefil && $secteurfil){

                        if ($serviceSecteur == $secteur && $date <= $serviceDate){

                            $filtreList[$index] = $service;
                            $index += 1;
                        }
                    }elseif ($datefil && !$secteurfil){

                        if ($date <= $serviceDate){

                            $filtreList[$index] = $service;
                            $index += 1;
                        }
                    }elseif ($secteurfil && !$datefil){

                        if ($serviceSecteur == $secteur){

                            $filtreList[$index] = $service;
                            $index += 1;
                        }
                    }elseif (!$secteurfil && !$datefil){

                        $filtreList[$index] = $service;
                        $index += 1;
                    }

                }
                $tour += 1;
            }
        }

//        $pages = $paginator->paginate(
//            $filtreList,
//            $request->query->getInt('page', 1), 10
//        );

        return $this->render('search/filter.html.twig',[
            'secteurs' => $secteurFilter,
            'services' => $filtreList,
        ]);
    }
}