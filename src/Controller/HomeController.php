<?php

namespace App\Controller;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{

    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $repo): Response
    {
       
        $produits = $repo->findAll();
       
        return $this->render('home/index.html.twig', compact('produits'));
    }

    #[Route("/show", name:"app_show_produits")]
    public function showProduct():Response
    {
        return $this->render('home/show.html.twig');
    }


    #[Route("/create", name: "app_create", methods: ["GET","POST"])]
    public function create(Request $request, EntityManagerInterface $em ): Response
    {
        //Instanciation de l'objet produit
        $produit = new Product();
            
        //Initialisation du Formulaire avec le FormBuilder de Symfony
        $form = $this->createFormBuilder($produit)

            ->add('productName', null, ['label' => 'Nom Produit'])
            ->add('productPrice', null, ['label' => ' Prix Produit'])
            ->add('productProvenance', null, ['label' => ' Provenance Produit'])
            ->getForm();

        
        //Récuper les donnees du Form: handleRequest = POST comme Methode
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            
            //Persistance & Enregistrement en BDD
            $em->persist($produit);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }

        return $this->render('home/create.html.twig', [
            "myForm" => $form->createView()
            ]);
    }

    #[Route("/login", name:"app_login")]
    public function login(): Response
    {
        return $this->render('login/login.html.twig');
    }

    #[Route("/register", name:"app_register")]
    public function register(): Response
    {
        return $this->render('login/register.html.twig');
    }
}
