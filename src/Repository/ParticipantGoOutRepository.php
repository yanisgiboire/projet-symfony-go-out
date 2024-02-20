<?php

namespace App\Repository;

use App\Entity\ParticipantGoOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ParticipantGoOut>
 *
 * @method ParticipantGoOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method ParticipantGoOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method ParticipantGoOut[]    findAll()
 * @method ParticipantGoOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantGoOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ParticipantGoOut::class);
    }

//    /**
//     * @return ParticipantGoOut[] Returns an array of ParticipantGoOut objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ParticipantGoOut
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
