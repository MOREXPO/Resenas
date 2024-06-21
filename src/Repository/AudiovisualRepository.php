<?php

namespace App\Repository;

use App\Entity\Audiovisual;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @extends ServiceEntityRepository<Audiovisual>
 *
 * @method Audiovisual|null find($id, $lockMode = null, $lockVersion = null)
 * @method Audiovisual|null findOneBy(array $criteria, array $orderBy = null)
 * @method Audiovisual[]    findAll()
 * @method Audiovisual[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AudiovisualRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Audiovisual::class);
    }

    public function save(Audiovisual $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Audiovisual $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByAverageRating($page = 1, $perPage = 30, $orderBy = 'DESC')
    {
        // Calcular el total de resultados
        $totalQb = $this->createQueryBuilder('a');
        $totalQb->select('COUNT(DISTINCT a.id)')
            ->join('App\Entity\Resena', 'r', Join::WITH, 'a.medio = r.medio')
            ->join('App\Entity\Valoracion', 'v', Join::WITH, 'r.id = v.resena');

        $totalResults = (int) $totalQb->getQuery()->getSingleScalarResult();

        // Calcular el número de páginas
        $totalPages = (int) ceil($totalResults / $perPage);

        // Obtener los resultados paginados
        $qb = $this->createQueryBuilder('a')
            ->select('a.id AS audiovisual_id', 'AVG(v.calificacion) AS media_valoracion')
            ->join('App\Entity\Resena', 'r', Join::WITH, 'a.medio = r.medio')
            ->join('App\Entity\Valoracion', 'v', Join::WITH, 'r.id = v.resena')
            ->groupBy('a.id')
            ->orderBy('media_valoracion', $orderBy)
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        $results = $qb->getQuery()->getResult();

        return [
            'totalPages' => $totalPages,
            'currentPage' => $page,
            'results' => $results
        ];
    }

    //    /**
//     * @return Audiovisual[] Returns an array of Audiovisual objects
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

    //    public function findOneBySomeField($value): ?Audiovisual
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
