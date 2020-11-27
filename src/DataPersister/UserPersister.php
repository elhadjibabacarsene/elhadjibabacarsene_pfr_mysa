<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserPersister implements DataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;


   public function __construct(EntityManagerInterface $entityManager)
   {
        $this->em = $entityManager;
   }

   public function supports($data): bool
   {
       // TODO: Implement supports() method.
       return $data instanceof User;
   }

    public function persist($data)
   {
       // TODO: Implement persist() method.
       $this->em->persist($data);
       $this->em->flush();
   }

   public function remove($data)
   {
       // TODO: Implement remove() method.
       $data->setArchivage(true);
       $this->em->persist($data);
       $this->em->flush();
   }

}