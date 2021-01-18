<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Config\Definition\Exception\Exception;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", methods={"GET"})
     */
    public function index(SerializerInterface $serializer): Response
    {
		$em = $this->getDoctrine()->getManager();

		$products = $em->getRepository(Product::class)->findAll();

		$response = new Response(
			$serializer->serialize($products, "json"),
    		Response::HTTP_OK,
		);
		return $response;
	}

    /**
     * @Route("/products", methods={"POST"})
     */
    public function addProduct(Request $request, SerializerInterface $serializer): Response
	{
		try 
		{
			$content = json_decode($request->getContent());
			if (!$content)
				throw new Exception("Invalid json format.");
			if (!Product::validate($content))
				throw new Exception("Invalid Product content.");
			$product = $serializer->denormalize($content, Product::class);
			$em = $this->getDoctrine()->getManager();
			$em->persist($product);
			$em->flush();
		
		} catch (Exception $e) {
				return new Response(
						$e->getMessage(),
						Response::HTTP_BAD_REQUEST,
				);
        }

		return new Response(
			"Product added.",
			Response::HTTP_OK,
		);
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
//    /**
//     * @Route("/product/{id<\d+>}", methods={"GET", "PUT", "DELETE"})
//     */
//    public function product(Request $request, int $id): Response
//    {
//		$em = $this->getDoctrine()->getManager();
//
//		$products = $em->getRepository(Product::class)->findOneBy(["id" => $id]);
//		// check and ?return
//
//		$response = new Response();
//		if ($request->isMethod('PUT'))
//		{
//			$product = json_decode($request->getContent());
//			// check validity
//			$entityManager->persist($product);
//			$entityManager->flush();
//		}
//		else if ($request->isMethod('DELETE'))
//		{
//			$entityManager->remove($product);
//			$entityManager->flush();
//		}
//
//		// set status
//		// set data
//		return $this->json([
//			'message' => json_encode($product),
//			'path' => 'src/Controller/ProductController.php',
//		]);
//    }
//    /**
//     * @Route("/product/{id<\d+>}", methods={"GET", "PUT", "DELETE"})
//     */
//    public function product(Request $request, int $id): Response
//    {
//		$em = $this->getDoctrine()->getManager();
//
//		$products = $em->getRepository(Product::class)->findOneBy(["id" => $id]);
//		// check and ?return
//
//		$response = new Response();
//		if ($request->isMethod('PUT'))
//		{
//			$product = json_decode($request->getContent());
//			// check validity
//			$entityManager->persist($product);
//			$entityManager->flush();
//		}
//		else if ($request->isMethod('DELETE'))
//		{
//			$entityManager->remove($product);
//			$entityManager->flush();
//		}
//
//		// set status
//		// set data
//		return $this->json([
//			'message' => json_encode($product),
//			'path' => 'src/Controller/ProductController.php',
//		]);
//    }
}
