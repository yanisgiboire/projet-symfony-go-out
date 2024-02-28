<?php

namespace App\Repository;

use App\Entity\GoOut;
use App\Entity\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GoOut>
 *
 * @method GoOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoOut[]    findAll()
 * @method GoOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @method GoOut[]    findBySearchParams()
 * @method GoOut[]    findBySearchParams($searchParams)
 */
class GoOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoOut::class);
    }

    private function createArchivedQueryBuilder()
    {
        return $this->createQueryBuilder('go_out')->join('go_out.status', 's');
    }

    public function findForIndex()
    {
        $queryBuilder = $this->createArchivedQueryBuilder()
            ->andWhere('s.libelle not in (:STATUS_PASSED, :STATUS_CREATED)')
            ->setParameter('STATUS_PASSED', Status::STATUS_PASSED)
            ->setParameter('STATUS_CREATED', Status::STATUS_CREATED);

        $monthAgoDate = new \DateTime();
        $monthAgoDate->modify('first day of last month')->setTime(0, 0, 0);

        $queryBuilder
            ->andWhere('go_out.startDateTime > :monthAgoDate')
            ->setParameter('monthAgoDate', $monthAgoDate);

        return $queryBuilder->getQuery()->getResult();
    }

    public function findBySearchParams($searchParams)
    {
        $queryBuilder = $this->createArchivedQueryBuilder();

        if (isset($searchParams['search']) && !empty($searchParams['search'])) {
            $queryBuilder
                ->andWhere('go_out.name LIKE :search')
                ->setParameter('search', '%'.$searchParams['search'].'%');
        }

        if (isset($searchParams['site']) && !empty($searchParams['site'])) {
            $queryBuilder
                ->andWhere('go_out.site = :site')
                ->setParameter('site', $searchParams['site']);
        }

        if (isset($searchParams['startDate']) && !empty($searchParams['startDate'])) {
            $startDate = \DateTime::createFromFormat('Y-m-d', $searchParams['startDate']);
            
            if ($startDate !== false) {
                $queryBuilder
                    ->andWhere('go_out.startDateTime > :startDate')
                    ->setParameter('startDate', $startDate);
            }
        }

        if (isset($searchParams['endDate']) && !empty($searchParams['endDate'])) {
            $endDate = \DateTime::createFromFormat('Y-m-d', $searchParams['endDate']);
            
            if ($endDate !== false) {
                $queryBuilder
                    ->andWhere('go_out.startDateTime < :endDate')
                    ->setParameter('endDate', $endDate);
            }
        }

        if (isset($searchParams['organizing']) && !empty($searchParams['organizing']) && isset($searchParams['userID']) && !empty($searchParams['userID'])) {
            $queryBuilder
                ->join('go_out.organizer', 'organizer')
                ->join('organizer.user', 'o')
                ->andWhere('o.id = :userID')
                ->setParameter('userID', $searchParams['userID'])
                ;
        } else {
            $queryBuilder
                ->andWhere('s.libelle not in (:STATUS_PASSED, :STATUS_CREATED)')
                ->setParameter('STATUS_PASSED', Status::STATUS_PASSED)
                ->setParameter('STATUS_CREATED', Status::STATUS_CREATED);
        }

        if ((isset($searchParams['registered']) && !empty($searchParams['registered'])) || (isset($searchParams['notRegistered']) && !empty($searchParams['notRegistered']))) {
            $queryBuilder
                ->join('go_out.participantGoOuts', 'pgo')
                ->join('pgo.participant', 'p');
        }

        if (isset($searchParams['registered']) && !empty($searchParams['registered']) && isset($searchParams['userID']) && !empty($searchParams['userID'])) {
            $queryBuilder
                ->andWhere('p.id = :userID')
                ->setParameter('userID', $searchParams['userID']);
        }

        if (isset($searchParams['notRegistered']) && !empty($searchParams['notRegistered']) && isset($searchParams['userID']) && !empty($searchParams['userID'])) {
            $queryBuilder
                ->andWhere('p.id <> :userID')
                ->setParameter('userID', $searchParams['userID']);
        }

        if (isset($searchParams['completed']) && !empty($searchParams['completed'])) {
            $queryBuilder->andWhere('s.libelle = :STATUS_PASSED');
        } else {
            $queryBuilder->andWhere('s.libelle <> :STATUS_PASSED');
        }

        $queryBuilder->setParameter('STATUS_PASSED', Status::STATUS_PASSED);

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
