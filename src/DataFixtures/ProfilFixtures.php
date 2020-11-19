<?php


namespace App\DataFixtures;


use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{


    public function load(ObjectManager $manager)
    {


        for ($i = 1; $i <= 4; $i++) {

            //On crée l'entité profil
            $profil = new Profil();
            $profil->setArchivage(0);

            if ($i == 1) {
                //On génère le profil admin
                $profil->setLibelle('admin');
                $this->setReference($i, $profil);
                $manager->persist($profil);

            } elseif ($i == 2) {
                //On génère le profil formateur
                $profil->setLibelle('formateur');
                $this->setReference($i, $profil);
            } elseif ($i == 3) {
                //On génère le profil apprenant
                $profil->setLibelle('apprenant');
                $this->setReference($i, $profil);
            } else {
                //On génère le profil cm
                $profil->setLibelle('cm');
                $this->setReference($i, $profil);
            }

            $manager->persist($profil);

            $manager->flush();
        }

    }

}