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
	 
	public function findAllButAdministrator($start, $limit)
	{
		return $em = $this->getEntityManager()
				->createQuery("SELECT ua FROM Admin\UserManagementBundle\Entity\UserAccount ua WHERE ua.username != 'Administrator'")
		        ->setMaxResults($limit)
		        ->setFirstResult($start)
				->getResult();
	}
	
	public function findByEmailCaseInsensitive($email)
	{
		return $em = $this->getEntityManager()
				->createQuery("SELECT ua FROM Admin\UserManagementBundle\Entity\UserAccount ua WHERE LOWER(ua.email) = LOWER('$email')")
				->getResult();
	}
}
