<?php

namespace App\Repository;

use App\Entity\Service;
use App\Entity\ServiceSearch;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Service>
 *
 * @method Service find($id, $lockMode = null, $lockVersion = null)
 * @method Service findOneBy(array $criteria, array $orderBy = null)
 * @method Service[]    findAll()
 * @method Service[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServiceRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly PaginatorInterface $paginationInterface
    )
    {
        parent::__construct($registry, Service::class);
    }

    public function save(Service $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Service $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllName(): array
    {
        return $this->createQueryBuilder('n')
            ->select("n.nom")
            ->getQuery()
            ->getResult();
    }

    public function getServicesByUser(User $user): mixed
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function getAllSector(): array{

        return $this->createQueryBuilder('n')
            ->select('n.secteur')
            ->getQuery()
            ->getResult();
    }


    public function getAllDate(): array{

        return $this->createQueryBuilder('n')
            ->select('n.updatedAt')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return Service[] Returns an array of Service objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Service
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
