<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

class PersonRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Person) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function search($research)
    {
        $qb = $this->createQueryBuilder('c');
        if (!empty($research)) {
            $qb->where(
                $qb->expr()->like('c.firstname', ':research'),
            );
            $qb->orWhere(
                $qb->expr()->like('c.lastname', ':research'),
            )
                ->setParameter('research', '%'.$research.'%');
        }
        $qb->orderBy('c.firstname', 'ASC');
        $qb->addOrderBy('c.lastname', 'ASC');

        return $qb->getQuery()->getResult();
    }

    public function findWithMedicalOffice(int $id): ?Person
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.medicalOffice', 'medicalOffice')
            ->addSelect('medicalOffice')
            ->where('c.id = :id')
            ->setParameter('id', $id);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
