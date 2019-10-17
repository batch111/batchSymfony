<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
//permet d'envoyer des fausses données 
class DataFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');

        //céer 3 categories fakées
        for($i =1; $i <= 3; $i++)
        {
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());
            $manager->persist($category);

            //créer entre 4 et 6 article

            for ($j = 1; $j <= mt_rand(4, 6); $j++)
            {
                $article = new Article();
                $content = '<p>' . join($faker->paragraphs(5), '</p> <p>') . '</p>'; //paragraph retourne un array
                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);

                $manager->persist($article); //faire persister les données dans la base de donnée

                for ($k = 1; $k <= mt_rand(4, 10); $k++)
                {
                    $comment = new Comment();
                    $content = '<p>' . join($faker->paragraphs(5), '</p> <p>') . '</p>';
                    $now = new \DateTime();
                    $interval = $now->diff($article->getCreatedAt());
                    $days = $interval->days;
                    $minimum = '-' . $days . 'days';
                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatetdAt($faker->dateTimeBetween($minimum))
                            ->setArticle($article);
                    $manager->persist($comment);
                }
            }

        }
        $manager->flush();
    }
}
