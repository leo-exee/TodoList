<?php
namespace App\Controller;

use App\Entity\Action;
use App\Form\ActionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[Route('/add', name: 'add_action_app')]
    public function addAction(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $securityContext = $this->container->get('security.authorization_checker');
        if ($securityContext->isGranted('ROLE_USER')) {
            $action = new Action;
            $form = $this->createForm(ActionType::class, $action);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $user = $this->getUser();
                $action->setUser($user);
                $action->setPicture(null);
                $entityManager->persist($action);
                $entityManager->flush();


                $uploadedFile = $form['picture']->getData();
                if($uploadedFile != null)
                {
                    $destination = $this->getParameter('kernel.project_dir').'/public/upload';
                    $newFilename = $action->getId() . "_" . "image.jpg";

                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );
                    $action->setPicture($newFilename);
                    $entityManager->flush();
                }

                $this->addFlash('success', 'Action added successfully');
                return $this->redirectToRoute('app_index');
            }

            return $this->render('form.html.twig', [
                'form' => $form->createView(), 'action' => $action
            ]);
        }

        $this->addFlash('danger', 'Not allow');
        return $this->redirectToRoute('app_index');
    }

    /**
     * @param int $id
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[Route('/edit/{id}', name: 'edit_action_app')]
    public function editAction(int $id, Request $request, EntityManagerInterface $entityManager) : Response
    {
        $user = $this->getUser();
        $action = $entityManager->getRepository(Action::class)->find($id);
        $securityContext = $this->container->get('security.authorization_checker');

        if($user == $action->getUser() && $securityContext->isGranted('ROLE_USER'))
        {
            $form = $this->createForm(ActionType::class, $action);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $entityManager->flush();

                $uploadedFile = $form['picture']->getData();
                if($uploadedFile != null)
                {
                    $destination = $this->getParameter('kernel.project_dir').'/public/upload';
                    $newFilename = $action->getId() . "_" . "image.jpg";

                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );
                    $action->setPicture($newFilename);
                    $entityManager->flush();
                }

                $this->addFlash('success', 'Action edited successfully');
                return $this->redirectToRoute('app_index');
            }

            return $this->render('form.html.twig', [
                'form' => $form->createView(), 'action' => $action
            ]);
        }

        $this->addFlash('danger', 'Not allow');
        return $this->redirectToRoute('app_index');
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    #[Route('/remove', name: 'remove_action_app')]
    public function removeAction(Request $request, EntityManagerInterface $entityManager) : Response
    {
        $user = $this->getUser();
        $action = $entityManager->getRepository(Action::class)->find($request->request->get('id'));
        $securityContext = $this->container->get('security.authorization_checker');

        if($user == $action->getUser() && $securityContext->isGranted('ROLE_USER')){
            $submittedToken = $request->request->get('token');
            if ($this->isCsrfTokenValid('delete-item'.$action->getId(), $submittedToken)) {
                $entityManager->remove($action);
                $entityManager->flush();
                $this->addFlash('success', 'Action deleted successfully');
                return $this->redirectToRoute('app_index');
            }
        }

        $this->addFlash('danger', 'Not allow');
        return $this->redirectToRoute('app_index');
    }
}