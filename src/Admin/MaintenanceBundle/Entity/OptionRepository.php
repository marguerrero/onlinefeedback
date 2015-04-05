<?php

namespace Admin\MaintenanceBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OptionRepository extends EntityRepository
{
    public function findAllOrderByComments($start, $limit, $cid)
     {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p FROM AdminMaintenanceBundle:Category p WHERE p.id = $cid ORDER BY p.id ASC"
            )
            ->setMaxResults($limit)
            ->setFirstResult($start)
            ->getResult();
     }
     
     public function getCommentCount($cid)
     {
         return $this->getEntityManager()
            ->createQuery(
                "SELECT COUNT(u) FROM AdminMaintenanceBundle:Category u WHERE u.id = $cid"
            )->getSingleScalarResult();
     }
    
}
