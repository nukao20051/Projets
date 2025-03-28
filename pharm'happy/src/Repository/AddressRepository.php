<?php

namespace App\Repository;

use App\Entity\Address;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Address>
 *
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function findByUser(Person $user): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.person = :user')
            ->setParameter('user', $user)
            ->orderBy('a.city', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
