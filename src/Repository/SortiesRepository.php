<?php

namespace App\Repository;

use App\Entity\Sorties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;


/**
 * @extends ServiceEntityRepository<Sorties>
 *
 * @method Sorties|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sorties|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sorties[]    findAll()
 * @method Sorties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortiesRepository extends ServiceEntityRepository
{
    private $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Sorties::class);
        $this->security = $security;
    }

    public function save(Sorties $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sorties $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Sorties[]
     */
    public function findAllByDateHeureDebut(): array
    {
        return $this->createQueryBuilder('sorties')
            ->orderBy('sorties.dateHeureDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByFiltres($filtres)
    {
        $qb = $this->createQueryBuilder('s')
            ->orderBy('s.dateHeureDebut', 'ASC');

        if (!empty($filtres['site'])) {
            $qb->andWhere('s.site = :site')
                ->setParameter('site', $filtres['site']);
        }

        if (!empty($filtres['dateDebut']) && !empty($filtres['dateFin'])) {
            $qb->andWhere('s.dateHeureDebut BETWEEN :dateDebut AND :dateFin')
                ->setParameter('dateDebut', $filtres['dateDebut'])
                ->setParameter('dateFin', $filtres['dateFin']);
        }

        if (!empty($filtres['organisateur'])) {
            $qb->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur', $this->security->getUser());
        }

        if (!empty($filtres['inscrit'])) {
            $qb->innerJoin('s.inscription', 'i')
                ->andWhere('i.participant = :participant')
                ->setParameter('participant', $this->security->getUser());
        }

        if (!empty($filtres['nonInscrit'])) {
            $qb->leftJoin('s.inscription', 'i', Join::WITH, 'i.participant = :participant')
                ->andWhere('i.id IS NULL')
                ->setParameter('participant', $this->security->getUser());
        }

        if (empty($filtres['passees'])) {
            $qb->andWhere('s.dateHeureDebut >= :now')
                ->setParameter('now', new \DateTime());
        }

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Sorties[] Returns an array of Sorties objects
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

//    public function findOneBySomeField($value): ?Sorties
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}