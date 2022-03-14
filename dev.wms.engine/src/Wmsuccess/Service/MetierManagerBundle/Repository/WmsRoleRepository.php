<?php

namespace App\Wmsuccess\Service\MetierManagerBundle\Repository;

use App\Wmsuccess\Service\MetierManagerBundle\Entity\WmsRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class WmsRoleRepository
 * @package App\Wmsuccess\Service\MetierManagerBundle\Repository
 */
class WmsRoleRepository extends ServiceEntityRepository
{
    /**
     * WmsRoleRepository constructor.
     * @param ManagerRegistry $_registry
     */
    public function __construct(ManagerRegistry $_registry)
    {
        parent::__construct($_registry, WmsRole::class);
    }
}
