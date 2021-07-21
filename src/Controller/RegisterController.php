<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request): Response
    {
        $user = new User(); // instancie ma class User 

        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user = $form->getData(); // injecte dans l'objet User toutes les données que tu récupères du formulaire

            // $doctrine = $this->getDoctrine()->getManager(); // appelle Doctrine par le getManager

            $this->entityManager->persist($user); // persister = figer la data car besoin de l'enregistrer

            $this->entityManager->flush(); // execute et enregistre dans la base de donnée
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
