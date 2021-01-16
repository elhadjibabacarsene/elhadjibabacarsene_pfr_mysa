<?php


namespace App\DataFixtures;


use App\Entity\Administrateurs;
use App\Entity\Apprenants;
use App\Entity\CommunityManager;
use App\Entity\Formateurs;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * L'encodeur de mots de passe
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

        public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 4; $i++) {
            for ($j = 0; $j < 5; $j++) {

                //On fait appel à Factory
                $faker = Factory::create('fr_FR');

                //On instancie l'entité User pour le password
                $user = new User();
                $passwordHash = $this->encoder->encodePassword($user, "password");


                if ($i === 1) {

                    //On génère les admins
                    $admin = new Administrateurs();
                    $admin->setPrenom($faker->firstName)
                        ->setNom($faker->lastName)
                        ->setEmail($faker->email)
                        ->setPassword($passwordHash)
                        ->setTelephone($faker->phoneNumber)
                        ->setGenre(0)
                        ->setArchivage(0)
                        ->setProfil($this->getReference($i));
                    $manager->persist($admin);

                } elseif ($i === 2) {

                    //On génère les formateurs
                    $formateur = new Formateurs();
                    $formateur->setPrenom($faker->firstName)
                        ->setNom($faker->lastName)
                        ->setEmail($faker->email)
                        ->setPassword($passwordHash)
                        ->setTelephone($faker->phoneNumber)
                        ->setGenre(0)
                        ->setArchivage(0)
                        ->setProfil($this->getReference($i));
                    $manager->persist($formateur);

                } elseif ($i === 3) {

                    //On génère les apprenants
                    $apprenant = new Apprenants();
                    $apprenant->setPrenom($faker->firstName)
                        ->setNom($faker->lastName)
                        ->setEmail($faker->email)
                        ->setPassword($passwordHash)
                        ->setTelephone($faker->phoneNumber)
                        ->setGenre(0)
                        ->setArchivage(0)
                        ->setProfil($this->getReference($i));
                    $manager->persist($apprenant);

                } else {

                    $cm = new CommunityManager();
                    $cm->setPrenom($faker->firstName)
                        ->setNom($faker->lastName)
                        ->setEmail($faker->email)
                        ->setPassword($passwordHash)
                        ->setTelephone($faker->phoneNumber)
                        ->setGenre(0)
                        ->setArchivage(0)
                        ->setProfil($this->getReference($i));
                    $manager->persist($cm);

                }
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            ProfilFixtures::class,
        );
    }
}