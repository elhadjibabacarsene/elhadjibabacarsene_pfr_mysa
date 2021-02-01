<?php

namespace App\Controller;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Entity\User;
use App\Repository\ProfilRepository;
use App\Repository\UserRepository;
use App\Service\ExtractData;
use App\Service\PhotoBlob;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
                            DenormalizerInterface $denormalizer,
                            ValidatorInterface $validator,
                            PhotoBlob $photoBlob)
    {
        //On récupère la requête(sous-format tab)
        $requestContent = $request->request->all();

        if (isset($requestContent) && !empty($requestContent)) {

            if (isset($requestContent['nom']) && isset($requestContent['prenom']) && isset($requestContent['email'])
                && isset($requestContent['genre']) && isset($requestContent['telephone'])
                && isset($requestContent['idProfil'])) {

                $requestContent['photo'] = $photoBlob->addPhoto($request,"photo");

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


                //On valide l'entité User
                $error = $validator->validate($user);
                if ($error) {
                    //S'il a erreur
                    return $this->json($error, Response::HTTP_BAD_REQUEST);
                } else {
                    $this->em->persist($user);
                    $this->em->flush();

                    return $this->json("succes", Response::HTTP_OK);
                }


            }
        }
    }

    /**
     * @Route(
     *     name="update_user",
     *     path="/api/admin/users/{id}",
     *     methods={"PUT"},
     *     defaults={
     *          "_controller" = "App\UserController::updateUser",
     *          "_api_resource_class" = User::class,
     *          "_api_item_operation_name" = "update_user"
     *     }
     * )
     * @param Request $request
     * @param ProfilRepository $profilRepository
     * @param UserRepository $userRepository
     * @param int $id
     * @param ExtractData $extractData
     * @return JsonResponse
     */
    public function updateUser(
        Request $request,
        ProfilRepository $profilRepository,
        UserRepository $userRepository,
        int $id,
        ExtractData $extractData
    )
    {
        //$contentRequest = $request->getContent();
        $contentRequest = $request->getContent();

        //le tableau qui contiendra les données extraites
        $finalData = [];

        $finalData = $extractData->extractAllData($contentRequest);
        //dd($finalData);


        //Récupérer le profil
        $profil = $profilRepository->find((int)$finalData['idProfil']);
        unset($finalData['idProfil']);

        //Gestion de l'image
        $file = fopen('php://memory', 'r+');
        fwrite($file, $finalData['photo']);
        rewind($file);
        //unset($finalData['photo']);

        //On récupère l'utilisateur
        $userDataPrevious = $userRepository->find($id);
        //dd($userDataPrevious);
        //On insère les données
        foreach ($finalData as $name => $value) {
            $method = 'set' . ucfirst($name);
            if (method_exists($userDataPrevious, $method)) {
                if ($method === 'setProfil')
                {//On renseigne l'id du profil
                    $value = $profilRepository->find((int)$value);
                }
                $userDataPrevious->$method($value);
            }
        }

        $this->em->persist($userDataPrevious);
        $this->em->flush();

        return $this->json("success", Response::HTTP_OK);

    }
}
