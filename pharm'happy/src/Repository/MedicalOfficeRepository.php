<?php

namespace App\Repository;

use App\Entity\MedicalOffice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MedicalOffice>
 *
 * @method MedicalOffice|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedicalOffice|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedicalOffice[]    findAll()
 * @method MedicalOffice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicalOfficeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedicalOffice::class);
    }

    //    /**
    //     * @return MedicalOffice[] Returns an array of MedicalOffice objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?MedicalOffice
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
