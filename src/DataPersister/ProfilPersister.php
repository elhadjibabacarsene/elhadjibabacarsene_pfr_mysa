<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Profil;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProfilPersister implements DataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;

    }

    //Quand intervernir ?
    public function supports($data): bool
    {
        return $data instanceof Profil;
    }

    //Si on veut faire des modifications avant de persister
    public function persist($data)
    {
        // TODO: Implement persist() method.
        $this->em->persist($data);
        $this->em->flush();
    }

    //Si on veut faire des suppressions avant de persister
    public function remove($data)
    {
        // TODO: Implement remove() method.
        $data->setArchivage(true);
        $allUserByProfil = $data->getUsers();
        foreach ($allUserByProfil as $user){
            $user->setArchivage(true);
            $this->em->persist($user);
        }

        $this->em->flush();

        //return new JsonResponse("success",401);


    }
}