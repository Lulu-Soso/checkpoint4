<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request): Response
    {
        // récupère tous les produits à l'aide de l'ORM doctrine
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        // On passe l'instance de la class Search dans le formulaire SearchType  
        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        // methode qui permet d'écouter si le formulaire a été posté
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search);
        }

        return $this->render('product/index.html.twig', [
            'products' => $products,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show($slug): Response
    {
        // récupère tous les produits à l'aide de l'ORM doctrine
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);


        // Si tu ne trouves pas de produit alors fais une redirection vers la liste des produits
        if (!$product) {
            return $this->redirectToRoute('products');
        }

        return $this->render('product/show.html.twig', [
            'product' => $product
        ]);
    }
}
