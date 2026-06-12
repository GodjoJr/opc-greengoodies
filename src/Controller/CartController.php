<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\CartProduct;
use App\Entity\Product;
use App\Repository\CartRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/cart', name: 'app_cart_')]
final class CartController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/add/{id}', name: 'add')]
    public function addToCart(Product $product, CartRepository $cartRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $cart = $cartRepository->findOneBy(['user' => $user]);

        if (!$cart) {
            $cart = new Cart();
            $cart->setUser($user);
            $cart->setCreatedAt(new \DateTimeImmutable());
        }

        $cart->setUpdatedAt(new \DateTimeImmutable());

        $cartProduct = null;
        foreach ($cart->getCartProducts() as $cp) {
            if ($cp->getProduct() === $product) {
                $cartProduct = $cp;
                break;
            }
        }

        if ($cartProduct) {
            $cartProduct->setQuantity($cartProduct->getQuantity() + 1);
        } else {
            $cartProduct = new CartProduct();
            $cartProduct->setProduct($product);
            $cartProduct->setQuantity(1);
            $cart->addCartProduct($cartProduct);
            $em->persist($cartProduct);
        }

        $em->persist($cart);
        $em->flush();

        return $this->redirectToRoute('app_cart_show');
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/', name: 'show')]
    public function show(CartRepository $cartRepository): Response
    {
        $cart = $cartRepository->findOneBy(['user' => $this->getUser()]);

        if (!$cart) {
            return $this->redirectToRoute('app_products_index');
        }

        $total = array_sum(
            array_map(
                fn(CartProduct $cp) => $cp->getProduct()->getPrice() * $cp->getQuantity(),
                $cart->getCartProducts()->toArray()
            )
            ?? [0]
        );

        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
            'cart_total' => $total
        ]);
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/empty', name: 'empty')]
    public function emptyCart(CartRepository $cartRepository, EntityManagerInterface $em): Response
    {
        $cart = $cartRepository->findOneBy(['user' => $this->getUser()]);

        if (!$cart) {
            return $this->redirectToRoute('app_cart_show');
        }

        $em->remove($cart);
        $em->flush();

        return $this->redirectToRoute('app_cart_show');
    }


}