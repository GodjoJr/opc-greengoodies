<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user', name: 'app_user_')]
final class UserController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/profile', name: 'profile')]
    public function profile(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy(
            ['user' => $this->getUser()],
            ['createdAt' => 'DESC']
        );

        $api_status = $this->getUser()->isApiAccess();

        return $this->render('user/profile.html.twig', [
            'orders' => $orders,
            'api' => $api_status
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/api/activate', name: 'profile_api_activate')]
    public function activateApi(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $user->setApiAccess(true);

        $em->flush();
        return $this->redirectToRoute('app_user_profile');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/api/deactivate', name: 'profile_api_deactivate')]
    public function deactivateApi(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $user->setApiAccess(false);

        $em->flush();
        return $this->redirectToRoute('app_user_profile');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/profile/delete', name: 'profile_delete')]
    public function deleteAccount(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $this->container->get('security.token_storage')->setToken(null);

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('app_home');
    }
}