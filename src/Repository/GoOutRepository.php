<?php

namespace App\Repository;

use App\Entity\GoOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GoOut>
 *
 * @method GoOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoOut[]    findAll()
 * @method GoOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method GoOut[]    findBySearchParams($searchParams)
 */
class GoOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoOut::class);
    }

    public function findBySearchParams($searchParams)
    {
        $queryBuilder = $this->createQueryBuilder('go_out');

        if (isset($searchParams['search']) && !empty($searchParams['search'])) {
            $queryBuilder
                ->andWhere('go_out.name LIKE :search')
                ->setParameter('search', '%'.$searchParams['search'].'%');
        }

        if (isset($searchParams['site']) && !empty($searchParams['site'])) {
            $queryBuilder
                ->andWhere('go_out.site_id = :site')
                ->setParameter('site', $searchParams['site']);
        }

        if (isset($searchParams['startDate']) && !empty($searchParams['startDate'])) {
            $queryBuilder
                ->andWhere('go_out.start_date_time > :startDate')
                ->setParameter('startDate', '%'.$searchParams['startDate'].'%');
        }

        if (isset($searchParams['endDate']) && !empty($searchParams['endDate'])) {
            $queryBuilder
            ->andWhere('go_out.start_date_time < :endDate')
            ->setParameter('endDate', '%'.$searchParams['endDate'].'%');
        }

        if (isset($searchParams['organizing']) && !empty($searchParams['organizing']) && isset($searchParams['userID']) && !empty($searchParams['userID'])) {
            $queryBuilder
            ->andWhere('go_out.user_id = :userID')
            ->setParameter('userID', '%'.$searchParams['userID '].'%');
        }
        
        
        
        return $queryBuilder->getQuery()->getResult();
    }

//    /**
//     * @return GoOut[] Returns an array of GoOut objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GoOut
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
