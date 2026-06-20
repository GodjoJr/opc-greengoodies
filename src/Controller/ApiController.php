<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

#[Route('/api', name: 'app_api_')]
final class ApiController extends AbstractController
{

    #[Route('/products', name: 'products', methods: ['GET'])]
    public function showProducts(ProductRepository $productRepository, SerializerInterface $serializer): Response
    {
        $products = $productRepository->findAll();

        $json = $serializer->serialize($products, 'json', [
            AbstractNormalizer::ATTRIBUTES => [
                'id',
                'name',
                'shortDescription',
                'fullDescription',
                'price',
                'picture',
            ],
        ]);

        return new JsonResponse($json, 200, [], true);
    }
}
