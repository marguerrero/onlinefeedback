<?php

namespace Admin\MaintenanceBundle\Entity;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
     /**
     * Find all employee by order by ID
     * 
     * @return object
     */
     
    public function findAllOrderByComments($start, $limit)
     {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT p FROM AdminMaintenanceBundle:Category p ORDER BY p.id ASC'
            )
            ->setMaxResults($limit)
            ->setFirstResult($start)
            ->getResult();
     }
     
     public function getCommentCount()
     {
         return $this->getEntityManager()
            ->createQuery(
                'SELECT COUNT(u) FROM AdminMaintenanceBundle:Category u'
            )->getSingleScalarResult();
     }
    
}
