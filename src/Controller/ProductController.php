<?php

namespace App\Controller;

use App\Entity\BannedProduct;
use App\Entity\Product;
use App\Entity\ProductRating;
use App\Entity\User;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET"})
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function getAllProducts(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->returnProductsData(),
            'user' => $this->getUser()
        ]);
    }

    /**
     * @Route("/new", name="product_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/ban/{product}/{user}", name="ban", methods={"POST", "GET"})
     * @param Product $product
     * @param User $user
     * @return RedirectResponse
     */
    public function banProduct(Product $product, User $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $bannedProduct = new BannedProduct();
        $bannedProduct->setProduct($product);
        $bannedProduct->setUser($user);

        $entityManager->persist($bannedProduct);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/rate", name="rate", methods={"POST", "GET"})
     * @param Request $request
     * @return RedirectResponse
     */
    public function rateProduct(Request $request)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $rate = $request->get('rate');
        $productId = $request->get('product');
        $product = $entityManager->getRepository(Product::class)->find($productId);

        $rateProduct = new ProductRating();
        $rateProduct->setProduct($product);
        $rateProduct->setRating($rate);
        $rateProduct->setUser($this->getUser());

        $entityManager->persist($rateProduct);
        $entityManager->flush();

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/filter", name="description_filter")
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function descriptionFilter(Request $request, ProductRepository $productRepository)
    {
        $text = $request->get('text');
        $type = $request->get('type');
        if ($type == 'name') {
            $products = $productRepository->findByName($text);
        } else {
            $products = $productRepository->findByDesc($text);

        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'user' => $this->getUser(),
        ]);
    }
}
