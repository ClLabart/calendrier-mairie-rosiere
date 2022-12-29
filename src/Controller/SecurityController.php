<?php

namespace App\Controller;

use App\Entity\Blog;
use App\Entity\Date;
use App\Entity\ResetPasswordRequest;
use App\Entity\User;
use App\Repository\BlogRepository;
use App\Repository\DateRepository;
use App\Repository\ResetPasswordRequestRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_calendar');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/supprimer-compte', name: 'app_delete_user')]
    public function deleteUser(UserRepository $userRepository, DateRepository $dateRepository, BlogRepository $blogRepository, ResetPasswordRequestRepository $resetRepository, ManagerRegistry $doctrine): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_calendar');
        }
        $userId = $this->getUser()->getId();

        $this->container->get('security.token_storage')->setToken(null);

        $entityManager = $doctrine->getManager();

        $user = $userRepository->find($userId);

        if ($user->getDates()) {
            $date = $dateRepository->findOneBy([
                'user' => $userId,
            ]);

            if ($date->getBlog()) {
                $blog = $blogRepository->find($date->getBlog()->getId());
                $entityManager->remove($blog);
            }

            $entityManager->remove($date);
        }

        $resetPassword = $resetRepository->findOneBy([
            'user' => $this->getUser(),
        ]);

        if ($resetPassword) {
            $entityManager->remove($resetPassword);
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('deleted','Votre compte a été supprimé.');
        return $this->redirectToRoute('app_calendar', [], Response::HTTP_SEE_OTHER);
    }
}
