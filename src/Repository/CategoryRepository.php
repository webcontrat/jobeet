<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryRepository
 *
 * @package App\Repository
 */
class CategoryRepository extends EntityRepository
{
    /**
     * @return Category[]
     */
    public function findWithActiveJobs(): array
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->innerJoin('c.jobs', 'j')
            ->where('j.expiresAt > :date')
            ->andWhere('j.activated = :activated')
            ->setParameter('date', new \DateTime())
            ->setParameter('activated', true)
            ->getQuery()
            ->getResult();
    }
}
