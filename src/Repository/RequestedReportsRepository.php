<?php

namespace App\Repository;

use App\Entity\RequestedReports;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RequestedReports|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestedReports|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestedReports[]    findAll()
 * @method RequestedReports[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestedReportsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RequestedReports::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('r')
            ->where('r.something = :value')->setParameter('value', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
