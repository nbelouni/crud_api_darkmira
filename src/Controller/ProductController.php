<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", methods={"GET"})
     */
    public function index(): Response
    {
		$em = $this->getDoctrine()->getManager();

		$products = $em->getRepository(Product::class)->findAll();

		$response = new Response(
			json_encode($products),
    		Response::HTTP_OK,
		);
		return $response;
	}

    /**
     * @Route("/products", methods={"POST"})
     */
    public function addProduct(Request $request): Response
    {
		$product = new Product();
//		$em = $this->getDoctrine()->getManager();

		$content = json_decode($request->getContent());
		// check validity
//		$em->persist($product);
//		$em->flush();

		$response = new Response(
			json_encode($content),
			Response::HTTP_OK,
		);
		// set status
		// set data
		return $response;
	}
    /**
     * @Route("/product/{id<\d+>}", methods={"GET", "PUT", "DELETE"})
     */
    public function product(Request $request, int $id): Response
    {
		$em = $this->getDoctrine()->getManager();

		$products = $em->getRepository(Product::class)->findOneBy(["id" => $id]);
		// check and ?return

		$response = new Response();
		if ($request->isMethod('PUT'))
		{
			$product = json_decode($request->getContent());
			// check validity
			$entityManager->persist($product);
			$entityManager->flush();
		}
		else if ($request->isMethod('DELETE'))
		{
			$entityManager->remove($product);
			$entityManager->flush();
		}

		// set status
		// set data
		return $this->json([
			'message' => json_encode($product),
			'path' => 'src/Controller/ProductController.php',
		]);
    }
}
