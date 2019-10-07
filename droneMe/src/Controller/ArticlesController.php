<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;  

class ArticlesController extends AbstractController
{
    /**
     * @Route("/articles", name="articles")
     */
    public function articles(ArticleRepository $repo)   //injection de dépendance ici 
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
        if($request->query->count() > 0)
        {
            $article = new Article();
            $article->setTitle($request->query->get('title'))
                    ->setContent($request->query->get('content'))
                    ->setImage($request->query->get('image'))
                    ->setCreatedAt(new \DateTime());
            $manager->persist($article);
            $manager->flush();

        }
        return $this->render('articles/create.html.twig');
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