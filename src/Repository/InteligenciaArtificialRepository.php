<?php

namespace App\Repository;

use App\Entity\InteligenciaArtificial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InteligenciaArtificial>
 *
 * @method InteligenciaArtificial|null find($id, $lockMode = null, $lockVersion = null)
 * @method InteligenciaArtificial|null findOneBy(array $criteria, array $orderBy = null)
 * @method InteligenciaArtificial[]    findAll()
 * @method InteligenciaArtificial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InteligenciaArtificialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InteligenciaArtificial::class);
    }

    public function save(InteligenciaArtificial $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(InteligenciaArtificial $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return InteligenciaArtificial[] Returns an array of InteligenciaArtificial objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InteligenciaArtificial
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
