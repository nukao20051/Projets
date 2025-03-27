<?php

namespace App\Repository;

use App\Entity\Medication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use function Symfony\Component\Clock\now;

/**
 * @extends ServiceEntityRepository<Medication>
 *
 * @method Medication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Medication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Medication[]    findAll()
 * @method Medication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medication::class);
    }

    /**
     * Research contacts according to the search criterion.
     *
     * @param string $research The research
     *
     * @return Medication[] Array of contacts
     */
    public function search(string $research, string $filter, string $inStock): array
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('m as medic');

        if (!empty($research)) {
            $qb->where(
                $qb->expr()->like('m.name', ':research'),
            )
                ->setParameter('research', '%'.$research.'%');
        }
        if ('p-asc' == $filter) {
            $qb->orderBy('m.price', 'ASC');
        } elseif ('p-desc' == $filter) {
            $qb->orderBy('m.price', 'DESC');
        } elseif ('s-asc' == $filter) {
            $qb->orderBy('stock', 'ASC');
        } elseif ('s-desc' == $filter) {
            $qb->orderBy('stock', 'DESC');
        }

        if ('' == $inStock) {
            $qb->leftJoin('m.samples', 's', 'WITH', 's.order IS NULL')
                ->groupBy('m.name')
                ->addSelect('COUNT(s.id) as stock')
                ->andWhere('s.order is null');
        } elseif ('in-stock' == $inStock) {
            $qb->leftJoin('m.samples', 's', 'WITH', 's.order IS NULL')
                ->groupBy('m.name')
                ->addSelect('COUNT(s.id) as stock')
                ->andWhere('s.order is null')
                ->having('COUNT(s.id) > 0');
        } elseif ('out-of-stock' == $inStock) {
            $qb->leftJoin('m.samples', 's', 'WITH', 's.order IS NULL')
                ->groupBy('m.name')
                ->addSelect('COUNT(s.id) as stock')
                ->andWhere('s.order is null')
                ->having('COUNT(s.id) = 0');
        }

        return $qb->getQuery()->execute();
    }

    public function expiredStock()
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('m as medic')
            ->leftJoin('m.samples', 's')
            ->groupBy('m.name')
            ->addSelect('COUNT(s.id) as stockExpired')
            ->where(':date > s.expiration')
            ->setParameter('date', now());

        return $qb->getQuery()->getResult();
    }

    public function expiredStockById(int $id)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('COUNT(s.id) as stockExpired')
            ->leftJoin('m.samples', 's', 'WITH', 's.order IS NULL AND :date > s.expiration')
            ->groupBy('m.name')
            ->where(':date > s.expiration')
            ->andWhere('m.id = :id')
            ->andWhere('s.order is null')
            ->setParameter('date', now())
            ->setParameter('id', $id);

        return $qb->getQuery()->getResult();
    }

    public function samplesWithoutOrder(int $id)
    {
        $qb = $this->createQueryBuilder('m');
        $qb->select('COUNT(s.id) as stock')
            ->leftJoin('m.samples', 's', 'WITH', 's.order IS NULL')
            ->groupBy('m.name')
            ->where('m.id = :id')
            ->andWhere('s.order is null')
            ->setParameter('id', $id);

        return $qb->getQuery()->getResult();
    }
}
