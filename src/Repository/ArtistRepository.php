<?php

namespace App\Repository;

use App\Entity\Artist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Artist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Artist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Artist[]    findAll()
 * @method Artist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArtistRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Artist::class);
    }

    /**
     * @param string $name
     * @return array
     */
    public function findByNamePart(string $name): array
    {
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.name like :name')
            ->setParameter('name', "%$name%")
            ->getQuery();

        return $qb->execute();
    }

    /**
     * @param string $search
     * @param int $offset
     * @param int $take
     * @return array
     */
    public function search(string $search = null, int $offset = 0, int $take): array
    {
        if (!$search) {
            return $this->findAll();
        }
        $qb = $this->createQueryBuilder('a')
            ->andWhere('a.name like :name')
            ->orWhere('a.description like :description')
            ->setParameter('name', "%$search%")
            ->setParameter('description', "%$search%")
            ->setFirstResult($offset)
            ->setMaxResults($take)
            ->orderBy('a.name')
            ->getQuery();
        return $qb->execute();
    }
}
