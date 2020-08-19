<?php

namespace App\Repository;

use App\Entity\Affiliate;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * Class AffiliateRepository
 *
 * @package App\Repository
 */
class AffiliateRepository extends EntityRepository
{
    /**
     * @param string $token
     *
     * @return Affiliate|null
     * @throws NonUniqueResultException
     */
    public function findOneActiveByToken(string $token) : ?Affiliate
    {
        return $this->createQueryBuilder('a')
            ->where('a.active = :active')
            ->andWhere('a.token = :token')
            ->setParameter('active', true)
            ->setParameter('token', $token)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
