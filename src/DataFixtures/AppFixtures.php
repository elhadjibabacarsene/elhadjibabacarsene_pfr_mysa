<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    /**
     * L'encodeur de mots de passe
     * @var UserPasswordEncoderInterface
     */
    private $encoder;


    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {

        // $product = new Product();
        // $manager->persist($product);

        //On fait appel à FAKER
        $faker = Factory::create('fr_FR');

        //On initialise un admin
        $user = new User();

        $passwordHash = $this->encoder->encodePassword($user, "password");

        $user->setPrenom($faker->firstName)
            ->setNom($faker->lastName)
            ->setEmail($faker->email)
            ->setPassword($passwordHash)
            ->setGenre(0)
            ->setArchivage(0)
            ->setTelephone($faker->phoneNumber);

        $manager->persist($user);

        $manager->flush();
    }
}
