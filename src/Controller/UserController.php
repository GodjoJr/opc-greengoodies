<?php

namespace App\Controller;

use App\Repository\OrderRepository;
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

        return $this->render('user/profile.html.twig', [
            'orders' => $orders
        ]);
    }
}