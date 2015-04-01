<?php

namespace Admin\UserManagementBundle\Entity;

use Doctrine\ORM\EntityRepository;

class UserAccountRepository extends EntityRepository
{    
     public function getNextId()
     {
     	$sql = "SELECT NEXTVAL('user_account_id_seq')";
		try
		{
			$stmt = $this->getEntityManager()
				->getConnection()
				->prepare($sql);
			$stmt->execute();
			return $stmt->fetchAll()[0]['nextval'];
		}
		catch(Exception $e)
		{
			return false; 
		}
     }
	 
	public function findAllButAdministrator($start, $limit, $sort)
	{
		$em = $this->getEntityManager();
		$qb = $em->createQueryBuilder();
		$qb->getEntityManager();
		$qb->select('ua')
			->from('Admin\UserManagementBundle\Entity\UserAccount', 'ua');
			//->orderBy('ua.active', 'DESC');
			
		if ( $sort )
		{
			$arr = json_decode($sort);
			$qb->orderBy('ua.'.($this->serializeProperty($arr[0]->property)), $arr[0]->direction);
		}
			$sql = $qb->addOrderBy('ua.username', 'ASC')
			        ->setMaxResults($limit)
			        ->setFirstResult($start)
			        ->getQuery();
			
			
		return $sql->getResult();
			
		
			//return 
				/*->createQuery("SELECT ua FROM Admin\UserManagementBundle\Entity\UserAccount ua WHERE ua.username != 'Administrator' ORDER BY ua.active, ORDER BY ua.user_role, ORDER BY ua.username")
		        ->setMaxResults($limit)
		        ->setFirstResult($start)->getResult();*/
				
				
		//return $em->orderBy('active ASC')->getResult();
	}
	
	public function findByEmailCaseInsensitive($email)
	{
		return $em = $this->getEntityManager()
				->createQuery("SELECT ua FROM Admin\UserManagementBundle\Entity\UserAccount ua WHERE LOWER(ua.email) = LOWER('$email')")
				->getResult();
	}

	function serializeProperty($prop) {
		if ( $prop == 'user_role' )
			return 'userRole';
		return $prop;
	}
}
