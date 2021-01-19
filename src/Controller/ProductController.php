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

        return new Response(
            $serializer->serialize($products, "json"),
            Response::HTTP_OK,
        );
    }

    /**
     * @Route("/products", methods={"POST"})
     */
    public function addProduct(Request $request, SerializerInterface $serializer): Response
    {
        try {
            $this->validateProductRequest($request);
            $product = $serializer->deserialize($request->getContent(), Product::class, "json", [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
            ]);
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
     * @Route("/product/{id<\d+>}", methods={"GET"})
     */
    public function getProduct(int $id, SerializerInterface $serializer): Response
    {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository(Product::class)->findOneBy(["id" => $id]);

        if (!$product) {
            return new Response(
                "No product for this id : {$id}.",
                Response::HTTP_BAD_REQUEST,
            );
        }

        return new Response(
            $serializer->serialize($product, "json"),
            Response::HTTP_OK,
        );
    }

    /**
     * @Route("/product/{id<\d+>}", methods={"PUT"})
     */
    public function editProduct(Request $request, int $id, SerializerInterface $serializer): Response
    {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository(Product::class)->findOneBy(["id" => $id]);

        if (!$product) {
            return new Response(
                "No product for this id : {$id}.",
                Response::HTTP_BAD_REQUEST,
            );
        }

        try {
            $this->validateProductRequest($request);
            $product = $serializer->deserialize($request->getContent(), Product::class, [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
            ]);
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
            "Product edited.",
            Response::HTTP_OK,
        );
    }
    
    /**
     * @Route("/product/{id<\d+>}", methods={"DELETE"})
     */
    public function removeProduct(Request $request, int $id): Response
    {
        $em = $this->getDoctrine()->getManager();

        $product = $em->getRepository(Product::class)->findOneBy(["id" => $id]);
        if (!$product) {
            return new Response(
                "No product for this id : {$id}.",
                Response::HTTP_BAD_REQUEST,
            );
        }

        if ($request->isMethod('DELETE')) {
            $em->remove($product);
            $em->flush();
        }

        return new Response(
            "Product removed.",
            Response::HTTP_OK,
        );
    }

    private function validateProductRequest($request) : void
    {
        $content = json_decode($request->getContent());
        if (!$content) {
            throw new Exception("Invalid json format.");
        }
        if (!Product::validate($content, "PUT")) {
            throw new Exception("Invalid object property.");
        }
    }
}
