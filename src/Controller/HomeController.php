<?php
namespace App\Controller;

use App\Repository\ActionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController  extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ActionRepository $actionRepository)
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('ROLE_USER')) {
            $actions = $actionRepository->findBy(['User' => $this->getUser()]);

            return $this->render('index.html.twig', ['actions' => $actions]);
        }
        return $this->redirectToRoute('app_login');
    }
}