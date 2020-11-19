<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserController extends AbstractController
{

    private $em;
    private $userRepository;

    /**
     * L'encodeur de mots de passe
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }


    /**
     * @Route(
     *     name="add_user",
     *     path="/api/admin/users",
     *     methods={"POST"},
     *     defaults={
     *          "_controller" = "App\UserController::addUser",
     *          "_api_resource_class" = User::class,
     *          "_api_collection_operation_name" = "add_user"
     *     }
     * )
     */
    public function addUser(Request $request, ProfilRepository $profilRepository,
                            DenormalizerInterface $denormalizer, ValidatorInterface $validator)
    {
        //On récupère la requête(sous-format tab)
        $requestContent = $request->request->all();

        if(isset($requestContent) && !empty($requestContent)){

            if(isset($requestContent['nom']) && isset($requestContent['prenom']) && isset($requestContent['email'])
                && isset($requestContent['password']) && isset($requestContent['genre']) && isset($requestContent['telephone'])
                && isset($requestContent['idProfil'])){

                //Gestion de l'image
                $requestContent['photo'] = $strm = fopen($request->files->get("photo"),'rb');

                //Gestion du profil
                $profil = $profilRepository->find($requestContent['idProfil']);


                //On élimine la clé idProfil et le password
                unset($requestContent['idProfil']);


                //On dénormalize la classe user puis on ajoute le profil et le password
                $user = $denormalizer->denormalize($requestContent,User::class);

                //Gestion du password
                $passwordHash = $this->encoder->encodePassword($user, "password");

                $user->setProfil($profil);
                $user->setPassword($passwordHash);

                unset($requestContent['password']);

                //On valide l'entité User
                $error = $validator->validate($user);
                if($error){
                    //S'il a erreur
                    return $this->json($error, Response::HTTP_BAD_REQUEST);
                }else{
                    $this->em->persist($user);
                    $this->em->flush();

                    return $this->json("succes", Response::HTTP_OK);
                }


            }
        }
    }



    /**
     * @Route(
     *     name="user_archivage",
     *     path="/api/admin/users/archivage",
     *     methods={"POST"},
     *     defaults={
     *          "_controller" = "App\UserController::archivageUser",
     *          "_api_resource_class" = User::class,
     *          "_api_collection_operation_name" = "user_archivage"
     *     }
     *)
     */
    public function archivageUser(Request $request, UserRepository $userRepository)
    {
        //On récupère la requête
        $requestContent = json_decode($request->getContent(), true);

        if(isset($requestContent) && !empty($requestContent))
        {

            $idUser = $requestContent['idUser'];
            $user = $userRepository->find($idUser);
            $user->setArchivage(true);

            $this->em->flush();

            return $this->json("success", Response::HTTP_OK);

        }

    }
}
