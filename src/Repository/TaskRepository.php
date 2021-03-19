<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }
    public function getCount(): int
    {
        $qb = $this->createQueryBuilder('t')
            ->select('COUNT(t.id) n')
            ->getQuery();
        // $nombre = $qb->execute();
        // dd($nombre[0]['n']);
        return $qb->getSingleScalarResult();
    }
    public function findByClassgroup($classgroup)
    {
        $qb = $this->createQueryBuilder('t')
            ->innerJoin('t.classgroup', 'g')
            ->where('g.id = :classgroup')
            ->setParameter('classgroup', $classgroup)
            ->orderBy('t.deadline', 'ASC')
            ->getQuery();
        return $qb->getResult();
    }
    // /**
    //  * @return Task[] Returns an array of Task objects
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
    public function findOneBySomeField($value): ?Task
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