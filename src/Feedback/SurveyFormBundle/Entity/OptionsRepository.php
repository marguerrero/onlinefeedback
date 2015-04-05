<?php

namespace Feedback\SurveyFormBundle\Entity;

use Doctrine\ORM\EntityRepository;

class OptionsRepository extends EntityRepository
{
    public function findAllOrderByComments($start, $limit, $cid)
     {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT p FROM FeedbackSurveyFormBundle:Options p WHERE p.qId = $cid ORDER BY p.optionDesc ASC"
            )
            ->setMaxResults($limit)
            ->setFirstResult($start)
            ->getResult();
     }
     
     public function getCommentCount($cid)
     {
         return $this->getEntityManager()
            ->createQuery(
                "SELECT COUNT(u) FROM FeedbackSurveyFormBundle:Options u WHERE u.qId = $cid"
            )->getSingleScalarResult();
     }
    
}
