<?php


namespace App\Event;


use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreated
{
    public function updateJwtData(JWTCreatedEvent $event)
    {
        //On récupère l'utilisateur
        $user = $event->getUser();

        //Enrichir le data
        $data = $event->getData();

        $data['id'] = $user->getId();
        $data['prenom'] = $user->getPrenom();
        $data['nom'] = $user->getNom();
        $data['adresseEmail'] = $user->getEmail();

        $event->setData($data);
    }
}