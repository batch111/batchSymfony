<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ArticlesController extends AbstractController
{
    
    /**
     * @Route("/allArticles", name="allArticles")
     */
    public function showAllArticle(ArticleRepository $repo)   //injection de dépendance ici 
    {
        // $repo =  $this->getDoctrine()->getRepository(Article::Class);  cette ligne n'est plus utile grâce à l'injection de dépendance (plus haut)
        $articles = $repo->findAll(); //methode qui permet de récupéré tout les élements de la base de donnée 
        return $this->render('articles/articles.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles,
        ]); 
    }

    /**
     * @Route("/articles/create", name="articleCreate")
     * @Route("/articles/{id}/edit", name="articleEdit")
     */
    public function formCreateEdit(Article $article = null, Request $request, ObjectManager $manager) //injection de dépendance pour récupérer la requête HTTP et pour solliciter le Manager, Article $article permet de trouver l'article passé par l'id dans la route afin d'afficher les données de chaque article dans les champs, le = null permet ici de ne pas avoir un erreur si nous voulons utiliser la route /articles/create pour creer un article.
    {
        if(!$article) //si l'article n'existe pas alors on créé un nouvelle article vide.
        {
            $article = new Article();
        }

        // methode en écrivant nous même les différents champs du form (non optimisé)
        // $form = $this->createFormBuilder($article)
        //     ->add('title', TextType::class)
        //     ->add('content', TextareaType::class)
        //     ->add('image', TextType::class)
        //     ->getForm();

        //methode grâce au CLI permet de générer automatiquement un form à partir d'une class ( Artcile ici)
        //permet également de ne pas dupliquer du code et d'avoir un code propre
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        dump($article);

        if($form->isSubmitted() && $form->isValid())
        {
            if(!$article->getId()) //permet de voir si un article existe si non alors on lui met un DateTime.
            {
                $article->setCreatedAt(new \DateTime());
            }
            $manager->persist($article); //persist en base de donnée
            $manager->flush(); //envoi des data

            return $this->redirectToRoute('showArticle', [
                'id' => $article->getId() //redirect vers la function show article mais besoin de l'id ( voir function show)
            ]);
        }
        //method sans vérification des données 
        // if($request->query->count() > 0)
        // {
        //     $article = new Article();
        //     $article->setTitle($request->query->get('title'))
        //             ->setContent($request->query->get('content'))
        //             ->setImage($request->query->get('image'))
        //             ->setCreatedAt(new \DateTime());
        //     $manager->persist($article);
        //     $manager->flush();
        // }

        return $this->render('articles/formArticle.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null //permet d'afficher un bouton différent dans le template si on est en edit mode ou en create ( voir create.html.twig)
        ]);
    }

    /**
     * @Route("/articles/delete/{id}", name="deleteArticle")
     */
    // function qui permet d\'afficher un article par son id
    public function deleteArticle($id, ObjectManager $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);
        $manager->remove($article);
        $manager->flush();
        return $this->redirectToRoute('allArticles');
    }

    /**
     * @Route("/articles/{id}", name="showArticle")
     */
    // function qui permet d\'afficher un article par son id
    public function showArticle($id)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);
        
        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }
}