<?php
namespace App\Controller;

use App\Repository\ActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController  extends AbstractController
{
    /**
     * @param ActionRepository $actionRepository
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[Route('/', name: 'app_index')]
    public function index(ActionRepository $actionRepository) : Response
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('ROLE_USER')) {
            $actions = $actionRepository->findBy(['User' => $this->getUser()]);

            return $this->render('index.html.twig', ['actions' => $actions]);
        }
        return $this->redirectToRoute('app_login');
    }
}