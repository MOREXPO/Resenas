<?php

namespace App\Repository;

use App\Entity\MedioPersonaEtiqueta;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MedioPersonaEtiqueta>
 *
 * @method MedioPersonaEtiqueta|null find($id, $lockMode = null, $lockVersion = null)
 * @method MedioPersonaEtiqueta|null findOneBy(array $criteria, array $orderBy = null)
 * @method MedioPersonaEtiqueta[]    findAll()
 * @method MedioPersonaEtiqueta[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MedioPersonaEtiquetaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MedioPersonaEtiqueta::class);
    }

    public function save(MedioPersonaEtiqueta $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MedioPersonaEtiqueta $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MedioPersonaEtiqueta[] Returns an array of MedioPersonaEtiqueta objects
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

//    public function findOneBySomeField($value): ?MedioPersonaEtiqueta
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
