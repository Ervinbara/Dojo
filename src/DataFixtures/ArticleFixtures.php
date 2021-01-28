<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;


class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');


        //Création de 3 fausse caté
        for($i = 1; $i<=3; $i++){
            $category = new Category();
            $category->setTitle($faker->sentence())
                     ->setDescription($faker->paragraph());
        
        $manager->persist($category);
        }

        //Création de faux article 
        for($j=1; $j<mt_rand(4, 6); $j++){
           
            $article = new Article();
            $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';
            $article->setTitle($faker->sentence)
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setCategory($category); //Video 3, 30 ème minutes

            $manager->persist($article);

            //On attribue des coms à nos articles
            $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

            $now = new \DateTime();
            $interval = $now->diff($article->getCreatedAt());
            $days = $interval->days;
            $minimum = '-' . $days . 'days'; //-100 jours

            for($k=1; $k<mt_rand(4, 10); $k++){
                $comment = new Comment();
                $comment->setAuthor($faker->name)
                        ->setContent($content)
                        ->setCreatedAt($faker->dateTimeBetween($minimum))
                        ->setArticle($article); //Attribution de l'article auxquel il appartient
                
            $manager->persist($comment);
            }           

        }
        

        $manager->flush(); 
    }
}
