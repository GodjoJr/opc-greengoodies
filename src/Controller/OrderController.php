<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/order', name: 'app_order_')]
final class OrderController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/add', name: 'add')]
    public function add(CartRepository $cartRepository, EntityManagerInterface $em, OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $faker = Factory::create('fr_FR');

        $cart = $cartRepository->findOneBy(['user' => $user]);

        if (!$cart || $cart->getCartProducts()->isEmpty()) {
            return $this->redirectToRoute('app_cart_show');
        }

        $today = new \DateTimeImmutable();
        $todayStart = $today->setTime(0, 0, 0);
        $todayEnd = $today->setTime(23, 59, 59);

        $order = new Order();
        $order->setUser($user);
        $order->setStatus('pending');
        $order->setCreatedAt(new \DateTimeImmutable());
        $order->setUpdatedAt(new \DateTimeImmutable());
        $count = $orderRepository->countOrdersToday($todayStart, $todayEnd);
        $order->setOrderNumber('C-' . date('md') . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT));
        $order->setShippingAddress($faker->streetAddress() . ', ' . $faker->postcode() . ' ' . $faker->city());

        foreach ($cart->getCartProducts() as $cartProduct) {
            $orderProduct = new OrderProduct();
            $orderProduct->setProduct($cartProduct->getProduct());
            $orderProduct->setProductName($cartProduct->getProduct()->getName());
            $orderProduct->setUnitPrice($cartProduct->getProduct()->getPrice());
            $orderProduct->setQuantity($cartProduct->getQuantity());

            $order->addOrderProduct($orderProduct);
            $em->persist($orderProduct);
        }

        $em->persist($order);

        foreach ($cart->getCartProducts() as $cartProduct) {
            $em->remove($cartProduct);
        }

        $em->flush();

        return $this->redirectToRoute('app_user_profile');
    }


}