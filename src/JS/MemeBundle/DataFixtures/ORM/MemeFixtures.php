<?php

namespace JS\MemeBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

use JS\CategoryBundle\Entity\Category;
use JS\CommentBundle\Entity\Comment;
use JS\MemeBundle\Entity\Meme;
use JS\MemeBundle\Entity\Score;

class MemeFixtures extends Fixture {
    public function load(ObjectManager $manager) {
        $faker = Factory::create();
    
        // Vars
        $nbCategories = 10;
        $nbMemes      = 100;
        $nbComments   = 1000;
        $nbPoints     = 10000;
    
        // Categories
        $categories = [];
        for ($i = 0; $i < $nbCategories; $i++){
            $category = new Category();
            $category->setTitle($faker->realText(15));
            $category->setImage('images/categories/blank.png');
    
            $categories[$i] = $category;
            
            //$manager->persist($categories[$i]);
        }
    
        // Memes
        $memes = [];
        for ($i = 0; $i < $nbMemes; $i++){
            $meme = new Meme();
            $meme->setTitle($faker->realText(100));
            $meme->setSource($faker->url);
            $meme->setAuthor($faker->userName);
            $meme->setUri("//via.placeholder.com/1280x720");
            $meme->setCategory($categories[rand(0, ($nbCategories)-1)]);
    
            $memes[$i] = $meme;
            
            //$manager->persist($meme);
        }
        
        // Comments
        $comments = [];
        for($i = 0; $i < $nbComments; $i++){
            $comment = new Comment();
            $comment
                ->setText($faker->realText(250))
                ->setAuthor($faker->userName)
                ->setMeme($memes[rand(0, ($nbMemes-1))])
            ;
            
            $comments[$i] = $comment;
            
            //$manager->persist($comment);
        }
        
        // Points
        $scores = [];
        for($i = 0; $i < $nbPoints; $i++){
            $point = rand(0,1);
            $point = ($point === 0) ? -1 : $point;
            
            $score = new Score();
            $score
                ->setPoint($point)
                ->setMeme($memes[rand(0, ($nbMemes-1))])
            ;
            
            $scores[$i] = $score;
            
            //$manager->persist($score);
        }
        
        
        $manager->flush();
        
    }
    
    public function getDependencies() {
        return [
            UserFixtures::class
        ];
    }
}