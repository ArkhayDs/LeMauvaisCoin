<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Annonce>
 *
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function save(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Annonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Annonce[]
     */
    public function findAllPublished(): array
    {
        return $this->createQueryBuilder("a")
            ->andWhere("a.createdAt is NOT NULL")
            ->orderBy('a.createdAt','DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Annonce[]
     */
    public function findAllPublishedById($id): array
    {
        return $this->createQueryBuilder("a")
            ->andWhere("a.id =:value")
            ->setParameter("value",$id)
            ->getQuery()
            ->getResult();
    }
}