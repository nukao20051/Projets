<?php

namespace App\Repository;

use App\Entity\Sample;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sample>
 *
 * @method Sample|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sample|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sample[]    findAll()
 * @method Sample[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SampleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sample::class);
    }

    public function searchByMedication(int $medicationId, \DateTime $expirationStart, \DateTime $expirationEnd): array
    {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.medication', 'cc');
        if (!empty($medicationId)) {
            $qb->where(
                $qb->expr()->eq('cc.id', ':medicationId')
            )
                ->setParameter('medicationId', $medicationId)
                ->andWhere('c.order is null');
        }

        if (isset($expirationStart) && isset($expirationEnd)) {
            $qb->andWhere(
                $qb->expr()->between('c.expiration', ':expirationStart', ':expirationEnd')
            )
                ->setParameter('expirationStart', $expirationStart)
                ->setParameter('expirationEnd', $expirationEnd);
        }

        $qb->orderBy('cc.name', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
