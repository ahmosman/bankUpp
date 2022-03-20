<?php

namespace App\Repository;

use App\Entity\Account;
use App\Entity\TransferHistory;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransferHistory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransferHistory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransferHistory[]    findAll()
 * @method TransferHistory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransferHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransferHistory::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TransferHistory $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(TransferHistory $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function createTransferHistoryQueryBuilder(User $user): QueryBuilder
    {
        $id = $user->getId();
        return $this->createQueryBuilder('t')
            ->innerJoin('t.fromAccount', 'fa')
            ->innerJoin('t.toAccount', 'ta')
            ->where('fa.user = :id')
            ->orWhere('ta.user = :id')
            ->setParameter('id', $id)
            ->orderBy('t.date', 'DESC')
            ;
    }

    // /**
    //  * @return TransferHistory[] Returns an array of TransferHistory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TransferHistory
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
