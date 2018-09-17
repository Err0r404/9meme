<?php

namespace JS\MemeBundle\DataFixtures\ORM;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

use JS\UserBundle\Entity\User;

class UserFixtures extends Fixture {
    
    public function load(ObjectManager $manager) {
        $faker   = Factory::create();
        
        // Vars
        $nbUsers = 15;
        
        $user = new User();
        $user->setEnabled(true);
        $user->setUsername("admin");
        $user->setEmail("admin@9meme.local");
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $encodedPassword = $encoder->encodePassword("admin", "");
        $user->setPassword($encodedPassword);
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $nbUsers--;
        
        $user = new User();
        $user->setEnabled(true);
        $user->setUsername("demo");
        $user->setEmail("demo@9meme.local");
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $encodedPassword = $encoder->encodePassword("demo", "");
        $user->setPassword($encodedPassword);
        $user->setRoles(['ROLE_USER']);
        
        $manager->persist($user);
        $nbUsers--;
        
        // Users
        $users = [];
        for ($i = 0; $i < $nbUsers; $i++) {
            $user = new User();
            $user->setEnabled(true);
            $user->setUsername($faker->userName);
            $user->setEmail($faker->safeEmail);
            $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
            $encodedPassword = $encoder->encodePassword($faker->password, "");
            $user->setPassword($encodedPassword);
            $user->setRoles(['ROLE_USER']);
            
            $users[$i] = $user;
            
            $manager->persist($users[$i]);
        }
        
        $manager->flush();
    }
}