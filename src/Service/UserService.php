<?php


namespace App\Service;


use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use App\Repository\ProfilRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserService
{
    public function createUser(
        $requestContent, ProfilRepository $profilRepository,
        DenormalizerInterface $denormalizer,
        PhotoBlob $photoBlob
    )
    {
        $requestContent['photo'] = $photoBlob->addPhoto($requestContent,"photo");
        //Gestion du profil
        $profil = $profilRepository->find($requestContent['idProfil']);
        //On élimine la clé idProfil et le password
        unset($requestContent['idProfil']);
        //On dénormalize la classe user puis on ajoute le profil et le password
        $user = $denormalizer->denormalize($requestContent, User::class);
        //Gestion du password
        $passwordHash = $this->encoder->encodePassword($user, "password");
        $user->setProfil($profil);
        $user->setPassword($passwordHash);
        unset($requestContent['password']);
    }
}