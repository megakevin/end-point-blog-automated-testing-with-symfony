<?php

namespace App\Repository;

use App\Entity\WeatherQuery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WeatherQuery|null find($id, $lockMode = null, $lockVersion = null)
 * @method WeatherQuery|null findOneBy(array $criteria, array $orderBy = null)
 * @method WeatherQuery[]    findAll()
 * @method WeatherQuery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WeatherQueryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeatherQuery::class);
    }

    public function add(WeatherQuery $weatherQuery)
    {
        $em = $this->getEntityManager();
        $em->persist($weatherQuery);
        $em->flush();
    }

    // /**
    //  * @return WeatherQuery[] Returns an array of WeatherQuery objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WeatherQuery
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
