<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
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
     */
    public function createArticle(Request $request, ObjectManager $manager) //injection de dépendance pour récupérer la requête HTTP et pour solliciter le Manager
    {
        $article = new Article;
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class)
            ->add('content', TextareaType::class)
            ->add('image', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();

        $form->handleRequest($request);
        dump($article);
        if($form->isSubmitted() && $form->isValid())
        {
            $article->setCreatedAt(new \DateTime());
            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('show_article', [
                'id' => $article->getId()   //recupère l'id de l'article créer pour rediriger directement vers la fonction show et avoir le bon routing
            ]);
        }
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

        return $this->render('articles/create.html.twig', [
            'formArticle' => $form->createView()
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
     * @Route("/articles/{id}", name="show_article")
     */
    // function qui permet d\'afficher un article par son id
    public function show($id)
    {
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id);
        
        return $this->render('articles/show.html.twig', [
            'article' => $article
        ]);
    }
}