<?php


namespace App\Service;


use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Profil;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;

class FilterUnarchivedObjectExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        // TODO: Implement applyToCollectionForUser() method.
        if(User::class === $resourceClass)
        {
            $queryBuilder->andWhere(sprintf("%s.archivage = 'false'",
            $queryBuilder->getRootAliases()[0]
            ));
        }
        // TODO: Implement applyToCollectionForProfil() method.
        if(Profil::class === $resourceClass)
        {
            $queryBuilder->andWhere(sprintf("%s.archivage = 'false'",
            $queryBuilder->getRootAliases()[0]
            ));
        }
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        // TODO: Implement applyToItemForUser() method.
        if(User::class === $resourceClass)
        {
            $queryBuilder->andWhere(sprintf("%s.archivage = 'false'",
                $queryBuilder->getRootAliases()[0]
            ));
        }
        // TODO: Implement applyToItemForProfil() method.
        if(Profil::class === $resourceClass)
        {
            $queryBuilder->andWhere(sprintf("%s.archivage = 'false'",
                $queryBuilder->getRootAliases()[0]
            ));
        }
    }
}