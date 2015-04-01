<?php

namespace Admin\UserManagementBundle\Entity;

use Doctrine\ORM\EntityRepository;

class SysUserActionLogsRepository extends EntityRepository
{    
     public function getNextId()
     {
     	$sql = "SELECT NEXTVAL('sys_user_action_logs_idsys_user_action_logs_seq')";
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
}
