<?php


namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Competences;
use Doctrine\ORM\EntityManagerInterface;

class CompetencesPersiter implements ContextAwareDataPersisterInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;


    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function supports($data, array $context = []): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof Competences;
    }

    public function persist($data, array $context = [])
    {
        // TODO: Implement persist() method.
        $this->em->persist($data);
        $this->em->flush();
        return $data;
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
        $data->setArchivage(true);
        $this->em->remove($data);
        $this->em->persist($data);
        //$this->em->remove($data);
        $this->em->flush();
    }
}