<?php
namespace Feedback\AnonymousBundle\Services;
/*


* ============================================================================


* $Header: EncryptDecrypt.php


*


* $Version: 1.0


* $Date: 02/15/2004 - 03/15/2004


* $By: Rommel M. Zamora


*


* $Revision: 1.1 (added decrypt_encrypt function for copying encryted data to another variable or query string)


* $Date: 07/28/2004


* $By: rommel m. zamora


*


* $Revision: --


* $Date: --


* $By: --


* ============================================================================


*


* License:	GNU Lesser General Public License (LGPL)


*


* Copyright (c) 2004 LINK2SUPPORT INC.  All rights reserved.


*


* This file is part of the L2S Online Application Framework


*


* This library is free software; you can redistribute it and/or


* modify it under the terms of the GNU Lesser General Public


* License as published by the Free Software Foundation; either


* version 2.1 of the License, or (at your option) any later version.





* This library is distributed in the hope that it will be useful,


* but WITHOUT ANY WARRANTY; without even the implied warranty of


* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU


* Lesser General Public License for more details.





* You should have received a copy of the GNU Lesser General Public


* License along with this library; if not, write to the Free Software


* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA


* ============================================================================


*/


class EncryptDecrypt


{


#-----------------------------------------------------------------------------#	


	function encrypt($txt,$CRYPT_KEY)


	{


	    if (!$txt && $txt != "0"){


	        return false;


	        exit;


	    }


	


	    if (!$CRYPT_KEY){


	        return false;


	        exit;


	    }


	


	    $kv = EncryptDecrypt::keyvalue($CRYPT_KEY);


	    $estr = "";


	    $enc = "";


	


	    for ($i=0; $i<strlen($txt); $i++) {


	        $e = ord(substr($txt, $i, 1));


	        $e = $e + $kv[1];


	        $e = $e * $kv[2];


	        (double)microtime()*1000000;


	        $rstr = chr(rand(65, 90));


	        $estr .= "$rstr$e";


	    }


	


	    return $estr;


	}


#-----------------------------------------------------------------------------#


	function decrypt($txt, $CRYPT_KEY)


	{


	    if (!$txt && $txt != "0"){


	        return false;


	        exit;


	    }


	


	    if (!$CRYPT_KEY){


	        return false;


	        exit;


	    }


	


	    $kv = EncryptDecrypt::keyvalue($CRYPT_KEY);


	    $estr = "";


	    $tmp = "";


	


	    for ($i=0; $i<strlen($txt); $i++) {


	        if ( ord(substr($txt, $i, 1)) > 64 && ord(substr($txt, $i, 1)) < 91 ) {


	            if ($tmp != "") {


	                $tmp = $tmp / $kv[2];


	                $tmp = $tmp - $kv[1];


	                $estr .= chr($tmp);


	                $tmp = "";


	            }


	        } else {


	            $tmp .= substr($txt, $i, 1);


	        }


	    }


	


	                $tmp = $tmp / $kv[2];


	                $tmp = $tmp - $kv[1];


	    $estr .= chr($tmp);


	


	    return $estr;


	}


#-----------------------------------------------------------------------------#


    function keyvalue($CRYPT_KEY){


    $keyvalue = "";


    $keyvalue[1] = "0";


    $keyvalue[2] = "0";


    for ($i=1; $i<strlen($CRYPT_KEY); $i++) 


    {


        $curchr = ord(substr($CRYPT_KEY, $i, 1));


        $keyvalue[1] = $keyvalue[1] + $curchr;


        $keyvalue[2] = strlen($CRYPT_KEY);


    }


        return $keyvalue;


    }


#-----------------------------------------------------------------------------#


	function decrypt_encrypt($txt, $CRYPT_KEY)


	{


		return EncryptDecrypt::encrypt(EncryptDecrypt::decrypt($txt, $CRYPT_KEY), $CRYPT_KEY);


	}


#-----------------------------------------------------------------------------#	


}


?>