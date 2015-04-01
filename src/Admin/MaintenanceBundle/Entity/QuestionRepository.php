<?php

namespace Admin\MaintenanceBundle\Entity;

use Doctrine\ORM\EntityRepository;

class QuestionRepository extends EntityRepository
{
    /**
     * get all question that of a category
     * 
     * @param category
     * @return Object
     */
     
     public function findAllQuestions($category)
     {
        return $this->getEntityManager()
                    ->createQuery(
                        'SELECT p FROM AdminMaintenanceBundle:Questions p
                        WHERE p.c = :c_id
                        ORDER BY p.id ASC'      
                    )->setParameter('c_id', $category)
                    ->getResult();    
     }
     
     public function findAllQuestionsWithLimit($category, $start, $limit)
     {
        return $this->getEntityManager()
                    ->createQuery(
                        'SELECT p FROM AdminMaintenanceBundle:Questions p
                        WHERE p.c = :c_id
                        ORDER BY p.id ASC'      
                    )->setParameter('c_id', $category)
                     ->setMaxResults($limit)
                     ->setFirstResult($start)
                    ->getResult();    
     }
     
     public function getQuestionCount($category)
     {
          return $this->getEntityManager()
                    ->createQuery(
                        'SELECT COUNT(p) FROM AdminMaintenanceBundle:Questions p
                        WHERE p.c = :c_id'      
                    )->setParameter('c_id', $category)
                    ->getSingleScalarResult();  
     }
}
