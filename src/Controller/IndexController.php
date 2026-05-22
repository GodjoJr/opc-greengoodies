<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ProductRepository $productRepository): Response
    {

    $products = $productRepository->findBy([], ['id' => 'DESC'], 9);
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'products' => $products
        ]);
    }
}
