<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Item::class);
    }

//    /**
//     * @return Item[] Returns an array of Item objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * return an item by slug and type parameters
     *
     * @param [type] $slug
     * @param [type] $type
     *
     * @return Item|null
     */
    public function findOneBySlugAndType($slug, $type): ?Item
    {
        return $this->createQueryBuilder('i')
            
            ->innerJoin('i.type', 't')
            ->addSelect('t')
            ->andWhere('i.slug = :slug')
            ->andWhere('t.id = :type')
            ->setParameter('slug', $slug)
            ->setParameter('type', $type)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
