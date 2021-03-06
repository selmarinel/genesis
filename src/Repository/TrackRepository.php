<?php

namespace App\Repository;

use App\Entity\Track;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Track|null find($id, $lockMode = null, $lockVersion = null)
 * @method Track|null findOneBy(array $criteria, array $orderBy = null)
 * @method Track[]    findAll()
 * @method Track[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrackRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Track::class);
    }

    public function search($search, $offset, $take)
    {
        if (!$search) {
            return $this->findAll();
        }
        $qb = $this->createQueryBuilder('t')
            ->andWhere('t.name like :name')
            ->setParameter('name', "%$search%")
            ->setFirstResult($offset)
            ->setMaxResults($take)
            ->orderBy('t.name')
            ->getQuery();
        return $qb->execute();
    }
}
