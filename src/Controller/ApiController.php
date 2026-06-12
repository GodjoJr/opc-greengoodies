<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api', name: 'app_api_')]
final class ApiController extends AbstractController
{
#[Route('/products', name: 'products', methods: ['GET'])]
    public function showProducts(ProductRepository $productRepository): Response
    {
       $products = $productRepository->findAll();

        $data = array_map(fn($p) => [
            'id'               => $p->getId(),
            'name'             => $p->getName(),
            'shortDescription' => $p->getShortDescription(),
            'fullDescription'  => $p->getFullDescription(),
            'price'            => $p->getPrice(),
            'picture'          => $p->getPicture(),
        ], $products);

        return $this->json($data);
    }
}
