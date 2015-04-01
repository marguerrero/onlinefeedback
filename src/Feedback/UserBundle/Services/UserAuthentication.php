<?php
/**
* UserAuthentication - authenticates user depending on type (normal login, ldap or tc/om authentication)
*
*
* @package 
* @subpackage class
* @author reymar.guerrero@concentrix.com
*/
namespace Feedback\UserBundle\Services;
use Feedback\UserBundle\LDAPAD;

class UserAuthentication
{
    protected $LDAPAD;
    protected $type;
    protected $master_password;
    protected $username;
    protected $password;


    /**
     * __construct - Dependency Injection (Constructor Injection) for User Authentication class
     *
     * @param String
     * @param String
     */
    public function __construct($LDAP, $master_password)
    {
        $this->LDAP = $LDAP;
        $this->master_password = $master_password;
    }

    /**
     * setLDAP - Dependency Injection (Setter Injection) for setting LDAP class
     *
     * @param \LDAPAP
     * @return null
     */
    public function setLDAP($LDAP)
    {
        $this->LDAP = $LDAP;
    }

    /**
     * setLoginInfo - Sets the username and password
     *
     * @param String $username
     * @param String $password
     * @return null
     */
    public function setLoginInfo($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        
        $this->LDAP->setCredential($username, $password);
        return;
    }
    /**
     * isValid - Checks if the username/password is correct
     *            - Returns true if the login credential is valid
     * 
     * @return boolean 
     */
    public function isValid()
    {
        
        list($bOK, $aLDAPList, $aGroupList) = $this->LDAP->fetchGroupList(true, true);
        
        if(($bOK) || ($this->password == $this->master_password))
        {
            return true;
        }
        else
        {
            return false;
        }

    }
    /**
     * logEntry - Logs the successful login entry
     *          - Depends on what type (Supportrix CRM, OCOC)
     *          - Commonly used on TC/OM authorization
     * @param Array 
     *             [user_id : id of the user_account]
     *             [trans_id : trans_id of the user_account]
     *             [status : status of the auth_log]
     *             [logid : login id(firstname.lastname) of the user_account]
     * @return boolean 
     */
    public function logEntry(Array $log_info)
    {
        // $trans_date = date('Y-m-d H:i:s');
        // $hash = md5($log_info['user_id'].$log_info['trans_id'].$log_info['status'].$trans_date);
        // switch ($this->type) {
        //     case 'supportrix_crm':
        //         $auth_user_log = new AuthUserLog;
        //         $auth->setAuthUserLogId($log_info['user_id']);
        //         $auth->setTransId($log_info['trans_id']);
        //         $auth->setTransDateTime($log_info['trans_date']);
        //         $auth->setStatus($log_info['status']);
        //         $auth->setLogid($log_info['logid']);
        //         $auth->setHash($hash);
        //         $auth->setAuthorizedBy($this->username);
                
        //         break;
            
        //     default:
        //         # code...
        //         break;
        // }
    }
}