<?php
namespace Feedback\UserBundle\Services;



class LDAPAD
{
	/************************************************************************** 
	 * Instance Variables
	 *************************************************************************/		
		
	protected $LDAPServer;
	protected $LDAPPort;
	protected $linkID;
	protected $bindRND;
	protected $bindPassword;
    protected $bindUser;

    
	/************************************************************************** 
	 * Constructor
	 *************************************************************************/		    
	//-- Modified By : Reymar Guerrero
	//-- Date Modified : June 06, 2014
	//-- Change the setup for constructor to properly inject in symfony2 service
	// public function __construct($userID, $password)
	// {
	// 	$this->bindUser = $userID;
	// 	$this->bindPassword = $password;
	// 	$this->intDefaultVariables();
	// }
	public function __construct()
	{
	}

	public function setCredential($userID, $password)
	{
		$this->bindUser = $userID;
		$this->bindPassword = $password;
		$this->intDefaultVariables();
	}
	/**
	 * initializes all class variables
	 *
	 */
    function intDefaultVariables()
    {
		//Connect is set to AD
		$this->LDAPServer = 'ph.gbsorg.net';
		$this->LDAPPort = 389;
		$this->bindRND = $this->bindUser."@ph.gbsorg.net";
		$this->linkID = ldap_connect($this->LDAPServer, $this->LDAPPort);
		
		/*Commented by ronald.echavez to Connect CES to AD*/
		//$this->LDAPServer = 'ldap.link2support.com';
		//$this->LDAPPort = 389;
		//$this->bindRND = "uid=".$this->bindUser.",ou=people,dc=link2support,dc=com";
		//$this->linkID = ldap_connect($this->LDAPServer, $this->LDAPPort);
    }

    /**
     * Setter for LDAPServer
     *
     * @param unknown_type $LDAPServer
     */
	public function setLDAPServer($LDAPServer)
	{
		$this->LDAPServer = $LDAPServer;
	}
	
	
	/**
	 * Setter for Setter for LDAPPort
	 *
	 * @param String $LDAPPort
	 */
	function setLDAPPort($LDAPPort)
	{
		$this->LDAPPort = $LDAPPort;
	}

	
	/**
	 * Setter for linkID
	 *
	 * @param String $linkID
	 */
	function setlinkID($linkID = NULL)
	{
		$this->linkID = $linkID;
	}
	

	/**
	 * Setter for bindRND
	 *
	 * @param String $bindRND
	 */
	function setbindRND($bindRND = NULL)
	{
		$this->bindRND = $bindRND;
	}
	

	/**
	 * Setter for bindPassword
	 *
	 * @param String $bindPassword
	 */
	function setbindPassword($bindPassword = NULL)
	{
		$this->bindPassword = $bindPassword;
	}
	

	/**
	 * Setter for bindUser
	 *
	 * @param String $bindUser
	 */
    function setbindUser($bindUser = NULL)
    {
	    $this->bindUser = $bindUser;
    }
    

    /**
     * Connet to LDAP Server
     *
     * @return Boolean
     */
	function connect()
	{
		if ($this->bindPassword != "" && $this->linkID)
		{
			if ($bind_result = @ldap_bind($this->linkID, $this->bindRND, $this->bindPassword))
			{				
 				ldap_close($this->linkID);
 				return true;
			}
			else
			{
 				return false;
			}
		}
		else
		{
	  		return false;
		}
	}
	

	/**
	 * Fetch email addresses
	 *
	 * @return Array
	 */
	function emailAddress()
	{
			$basedn = "ou=people,dc=link2support,dc=com";
			$justthese = array("cn", "mail");
			$LDAPList = array();
			$bind_result = @ldap_bind($this->linkID, $this->bindRND, $this->bindPassword);
			$sr=ldap_list($this->linkID, $basedn, "objectClass=*", $justthese);
			$info = ldap_get_entries($this->linkID, $sr);

			for ($i=0; $i<$info["count"]; $i++)
			{
				$LDAPList[]= array("cn" => $info[$i]["cn"][0],"mail" => $info[$i]["mail"][0]);
		    }
	  		return $LDAPList;
	}
	

	/**
	 * Fetch MailingList
	 *
	 * @return Array
	 */
	function maillingList()
	{
			$basedn = "ou=MailList,dc=link2support,dc=com";
			$justthese = array("cn", "mail");
			$LDAPList = array();
			$bind_result = @ldap_bind($this->linkID, $this->bindRND, $this->bindPassword);
			$sr=ldap_list($this->linkID, $basedn, "objectClass=*", $justthese);

			$info = ldap_get_entries($this->linkID, $sr);

			for ($i=0; $i<$info["count"]; $i++)
			{
				$LDAPList[]= array("cn" => $info[$i]["cn"][0],"mail" => $info[$i]["mail"][0]);
		    }
	  		return $LDAPList;
	}
	
	/*
	 *  Get EmailAddress List
	 *  for addressbook
	 *  Added by: francis.barretto
	 */	
	
	function getEmailAddressBook($query){
						
		$basedn = "DC=ph,DC=gbsorg,DC=net";
		$justthese = array("samaccountname", "userprincipalname");
		$aLDAPList = array();		
		$bind_result = @ldap_bind($this->linkID, $this->bindRND, $this->bindPassword);
		
		$letterArray = array("a","b","c","d","e","f",
						     "g","h","i","j","k","l",
						     "m","n","o","p","q","r",
						     "s","t","u","v","w","x",
						     "y","z");
		
		$filter = "(userprincipalname=" . $query . "*)";
		
		$sr=ldap_search($this->linkID, $basedn, $filter, $justthese);
		
		$info = ldap_get_entries($this->linkID, $sr);
		
		for ($i = 0; $i < $info["count"]; $i++)
		{
			$aLDAPList[]= array("cn" => $info[$i]["samaccountname"][0],"mail" => $info[$i]["userprincipalname"][0]);
		}
		
		unset($filter);
		unset($sr);
		unset($info);
		
  		return $aLDAPList;		
	}
	
	
	/**
	* Fetch grouplist by gretags
	*
	*/
	function fetchGroupList($bGetEmail=false, $bGetGroupList=false, $replace_user = false)
	{
		error_reporting(0);
        if ($bGetEmail == true && $bGetGroupList == true)
            $vReturn = array(false, '', '');
        else
            $vReturn = false;
		
		//Connect CES to AD
		$bGetEmail = false;
		$bGetGroupList = false;
		
		if ($this->bindPassword != "" && $this->linkID)
		{
 			$bind_result = @ldap_bind($this->linkID, $this->bindRND, $this->bindPassword);
			
 			if ($bind_result)
 			{
                $vEmail = '';
	 			if ($bGetEmail)
	 			{
	     			#--->>> get email list and save to session
					$basedn = "ou=MailList,dc=link2support,dc=com";
					$justthese = array("cn", "mail");
					$aLDAPList = array();
					$sr=ldap_list($this->linkID, $basedn, "objectClass=*", $justthese);
					$info = ldap_get_entries($this->linkID, $sr);
					
					for ($i=0; $i<$info["count"]; $i++)
					{
						$aLDAPList[]= array("cn" => $info[$i]["cn"][0], "mail" => $info[$i]["mail"][0]);
				    }
					
					$basedn = "ou=people,dc=link2support,dc=com";
					$sr=ldap_list($this->linkID, $basedn, "ou=*", $justthese);
					$info = ldap_get_entries($this->linkID, $sr);
					
					for ($i=0; $i<$info["count"]; $i++)
					{
						$aLDAPList[]= array("cn" => $info[$i]["cn"][0],
											"mail" => $info[$i]["mail"][0]);
				    }
					
				    //array_multisort($aLDAPList);
			  		$vEmail = $aLDAPList;
		  		}
				
                $vGroupList = '';
	 			if ($bGetGroupList)
	 			{
	     			#--->>> get group list and save to session
					$basedn = "ou=people,dc=link2support,dc=com";
                    if ($replace_user)
                        $filter = "(uid=" . $replace_user . ")";
                    else
                        $filter = "(uid=" . $this->bindUser . ")";
                    $sr = ldap_search($this->linkID, $basedn, $filter);
                    if (ldap_count_entries($this->linkID, $sr) > 0) {
                        $info = ldap_get_entries($this->linkID, $sr);
                        $cnt = 0;
                        while (1) {
                            if ($info[0]['comment'][$cnt] == '') break;
							$sGroup = strtolower($info[0]['comment'][$cnt]);
                            if ($vGroupList == '')
                                $vGroupList = "'$sGroup'";
                            else
                                $vGroupList = $vGroupList . ",'$sGroup'";
                            $cnt++;
                        }
                        if ($vGroupList == '') $vGroupList = "'group'";
                    }
 		  		}
				
                if ($bGetEmail == false && $bGetGroupList == false)
		  			//$vReturn = true;
					$vReturn = array(true, $vEmail, $vGroupList);
                else if ($bGetEmail == false && $bGetGroupList == true)
                    $vReturn = $vGroupList;
                else if ($bGetEmail == true && $bGetGroupList == false)
                    $vReturn = $vEmail;
                else
                    $vReturn = array(true, $vEmail, $vGroupList);
 			}
  		}
		
  		ldap_close($this->linkID);
 		//print "<Pre>";
 		//print_r($vReturn);
 		//print "</pre>";
  		return $vReturn;
	}
	
}
?>