<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function search(int $id, string $research, string $filter, string $orderState, Security $security): array
    {
        $qb = $this->createQueryBuilder('o');
        $qb->select('o as order')
            ->join('o.person', 'p')
            ->join('o.address', 'a')
            ->addSelect('p.id as id')
            ->addSelect('p.firstname as firstname')
            ->addSelect('p.lastname as lastname')
            ->addSelect('a.street as street')
            ->addSelect('a.city as city')
            ->addSelect('a.pc as pc')
            ->addSelect('a.num as num');

        if (!($security->isGranted('ROLE_ADMIN') || $security->isGranted('ROLE_MANAGER'))) {
            $user = $security->getUser();
            $qb->andWhere('o.person = :person');
            $qb->setParameter('person', $user);
        }

        if ('inPreparation' === $orderState) {
            $qb->andWhere('o.orderState = :state');
            $qb->setParameter('state', 'En préparation');
        } elseif ('beingDelivered' === $orderState) {
            $qb->andWhere('o.orderState = :state');
            $qb->setParameter('state', 'En cours de livraison');
        } elseif ('delivered' === $orderState) {
            $qb->andWhere('o.orderState = :state');
            $qb->setParameter('state', 'Livré');
        }

        if (!empty($id)) {
            $qb->andWhere('p.id = :id')
               ->setParameter('id', $id);
        }

        if (!empty($research)) {
            $qb->andWhere(
                $qb->expr()->like('p.firstname', ':research'),
            );
            $qb->orWhere(
                $qb->expr()->like('p.lastname', ':research'),
            )
                ->setParameter('research', '%'.$research.'%');
        }

        if ('d-asc' === $filter) {
            $qb->orderBy('o.deliveryDate', 'ASC');
        } elseif ('d-desc' === $filter) {
            $qb->orderBy('o.deliveryDate', 'DESC');
        } elseif ('m-asc' === $filter) {
            $qb->orderBy('o.totalPrice', 'ASC');
        } elseif ('m-desc' === $filter) {
            $qb->orderBy('o.totalPrice', 'DESC');
        }

        $qb->addOrderBy('p.firstname', 'ASC')
        ->addOrderBy('p.lastname', 'ASC');

        return $qb->getQuery()->execute();
    }
}
