<?php

namespace App\Repository;

use App\Entity\UrlStats;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UrlStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method UrlStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method UrlStats[]    findAll()
 * @method UrlStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UrlStatsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UrlStats::class);
    }

    public function countUniqueUrlsBetween(\DateTimeImmutable $start, \DateTimeImmutable $end): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.url)')
            ->where('u.createdDate BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countUniqueUrlsByDomain(string $domain): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(DISTINCT u.url)')
            ->where('u.url LIKE :domain')
            ->setParameter('domain', '%' . $domain . '%')
            ->getQuery()
            ->getSingleScalarResult();
    }
}
