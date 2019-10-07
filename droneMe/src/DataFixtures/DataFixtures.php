<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;

//permet d'envoyer des fausses données 
class DataFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++)
        {
            $article = new Article();
            $article->setTitle("Titre de l'article numéro : $i")
                    ->setContent("<p> Ceci est le content de l'article $i </p>")
                    ->setImage("http://placehold.it/350x150")
                    ->setCreatedAt(new \DateTime());
            $manager->persist($article); //faire persister les données dans la base de donnée
        }

        $manager->flush();
    }
}
